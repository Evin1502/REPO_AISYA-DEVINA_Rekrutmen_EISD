<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PointHistoryController extends Controller
{
    /**
     * Use case: "Melihat riwayat poin" (Resident).
     */
    public function index(Request $request): View
    {
        $pointHistories = $request->user()
            ->pointHistories()
            ->with('pickupRequest')
            ->latest()
            ->paginate(15);

        return view('resident.point-histories.index', compact('pointHistories'));
    }
}
