<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PointExchange;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PointExchangeController extends Controller
{
    public function index(Request $request): View
    {
        $query = PointExchange::with(['user', 'reward']);

        if ($request->filled('status') && in_array($request->status, ['pending', 'approved', 'rejected'], true)) {
            $query->where('status', $request->status);
        }

        return view('admin.point-exchanges.index', [
            'pointExchanges' => $query->latest()->paginate(10)->withQueryString(),
            'currentStatus' => $request->status,
        ]);
    }

    /**
     * Approve: poin sudah dipotong saat resident menukar, jadi tidak ada
     * perubahan poin/stok — hanya ubah status.
     */
    public function approve(Request $request, PointExchange $pointExchange): RedirectResponse
    {
        abort_unless($pointExchange->status === 'pending', 422, 'Hanya penukaran berstatus pending yang bisa disetujui.');

        $pointExchange->update(['status' => 'approved']);

        return back()->with('success', 'Penukaran poin disetujui.');
    }

    /**
     * Reject: kembalikan (refund) poin ke resident dan pulihkan stok reward,
     * lalu catat PointHistory(type=refund) dalam satu transaksi DB.
     */
    public function reject(Request $request, PointExchange $pointExchange): RedirectResponse
    {
        abort_unless($pointExchange->status === 'pending', 422, 'Hanya penukaran berstatus pending yang bisa ditolak.');

        DB::transaction(function () use ($pointExchange) {
            $pointExchange->update(['status' => 'rejected']);

            $pointExchange->user()->increment('points', $pointExchange->points_used);
            $pointExchange->reward()->increment('stock');

            $pointExchange->user->pointHistories()->create([
                'points' => $pointExchange->points_used,
                'type' => 'refund',
                'description' => 'Refund poin karena penukaran reward "' . $pointExchange->reward->name . '" ditolak.',
            ]);
        });

        return back()->with('success', 'Penukaran poin ditolak. Poin telah dikembalikan dan stok dipulihkan.');
    }
}