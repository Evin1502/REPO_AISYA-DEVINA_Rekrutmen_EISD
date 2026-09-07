<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\PointExchangeException;
use App\Http\Controllers\Controller;
use App\Models\PointExchange;
use App\Services\PointRewardService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PointExchangeController extends Controller
{
    public function __construct(private readonly PointRewardService $pointRewardService) {}

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
     * Approve: poin & stok sudah dipotong saat resident menukar; untuk reward
     * saldo, cash_balance pengguna dikredit (simulasi, tanpa payment gateway).
     */
    public function approve(Request $request, PointExchange $pointExchange): RedirectResponse
    {
        $this->authorize('approve', $pointExchange);

        try {
            $this->pointRewardService->approve($pointExchange);
        } catch (PointExchangeException $e) {
            abort(422, $e->getMessage());
        }

        return back()->with('success', 'Penukaran poin disetujui.');
    }

    /**
     * Reject: kembalikan (refund) poin ke resident dan pulihkan stok reward,
     * lalu catat PointHistory(type=refund) dalam satu transaksi DB (lihat service).
     */
    public function reject(Request $request, PointExchange $pointExchange): RedirectResponse
    {
        $this->authorize('reject', $pointExchange);

        try {
            $this->pointRewardService->reject($pointExchange);
        } catch (PointExchangeException $e) {
            abort(422, $e->getMessage());
        }

        return back()->with('success', 'Penukaran poin ditolak. Poin telah dikembalikan dan stok dipulihkan.');
    }
}
