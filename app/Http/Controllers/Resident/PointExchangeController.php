<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PointExchangeController extends Controller
{
    /**
     * Use case: "Melihat riwayat penukaran poin" (Resident).
     */
    public function index(Request $request): View
    {
        $pointExchanges = $request->user()
            ->pointExchanges()
            ->with('reward')
            ->latest()
            ->paginate(10);

        return view('resident.point-exchanges.index', compact('pointExchanges'));
    }

    /**
     * Use case: "Melakukan penukaran poin" (Resident) <<Include>> "Mengelola penukaran poin" (Admin).
     *
     * Poin langsung dipotong & stok reward langsung berkurang saat pengajuan dibuat
     * (status 'pending'), lalu menunggu Admin approve/reject.
     */
    public function store(Request $request, Reward $reward): RedirectResponse
    {
        $user = $request->user();

        if ($reward->stock < 1) {
            return back()->with('error', 'Stok reward ini sudah habis.');
        }

        if ($user->points < $reward->points_required) {
            return back()->with('error', 'Poin kamu tidak cukup untuk menukar reward ini.');
        }

        DB::transaction(function () use ($user, $reward) {
            $user->decrement('points', $reward->points_required);
            $reward->decrement('stock');

            $user->pointExchanges()->create([
                'reward_id' => $reward->id,
                'points_used' => $reward->points_required,
                'status' => 'pending',
            ]);

            $user->pointHistories()->create([
                'points' => -$reward->points_required,
                'type' => 'redeem',
                'description' => 'Penukaran poin untuk reward: ' . $reward->name,
            ]);
        });

        return redirect()
            ->route('resident.point-exchanges.index')
            ->with('success', 'Penukaran poin untuk "' . $reward->name . '" berhasil diajukan, menunggu persetujuan Admin.');
    }
}
