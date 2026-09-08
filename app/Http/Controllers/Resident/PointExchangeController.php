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

    public function index(Request $request): View
    {
        $pointExchanges = $request->user()
            ->pointExchanges()
            ->with('reward')
            ->latest()
            ->paginate(10);

        return view('resident.point-exchanges.index', compact('pointExchanges'));
    }

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
