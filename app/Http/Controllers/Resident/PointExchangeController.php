<?php

namespace App\Http\Controllers\Resident;

use App\Exceptions\PointExchangeException;
use App\Http\Controllers\Controller;
use App\Models\Reward;
use App\Services\PointRewardService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PointExchangeController extends Controller
{
    public function __construct(private readonly PointRewardService $pointRewardService) {}

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
     * Alur bisnis (lihat PointRewardService@redeem):
     *  1. Validasi kecukupan poin & stok reward.
     *  2. Potong poin & stok, catat transaksi (Redemption History Log) + mutasi
     *     PointHistory(type=redeem).
     *  3. Reward SALDO otomatis disetujui & cash_balance langsung dikredit.
     *     Reward BARANG berstatus pending menunggu Admin approve/reject.
     */
    public function store(Request $request, Reward $reward): RedirectResponse
    {
        try {
            $exchange = $this->pointRewardService->redeem($request->user(), $reward);
        } catch (PointExchangeException $e) {
            return back()->with('error', $e->getMessage());
        }

        $message = $exchange->isApproved()
            ? '"'.$reward->name.'" berhasil ditukar, saldo langsung ditambahkan ke Cash Balance kamu.'
            : 'Penukaran poin untuk "'.$reward->name.'" berhasil diajukan, menunggu persetujuan Admin.';

        return redirect()
            ->route('resident.point-exchanges.index')
            ->with('success', $message);
    }
}
