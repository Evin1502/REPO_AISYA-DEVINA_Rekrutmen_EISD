<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PickupRequest;
use App\Models\PointExchange;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $collected = PickupRequest::where('status', 'collected');

        $stats = [
            'totalResidents' => User::where('role', 'resident')->count(),
            'totalCollectors' => User::where('role', 'collector')->count(),
            'pendingPickups' => PickupRequest::where('status', 'pending')->count(),
            'todayCollected' => PickupRequest::where('status', 'collected')
                ->whereDate('updated_at', Carbon::today())
                ->count(),
            'pendingExchanges' => PointExchange::where('status', 'pending')->count(),
            'totalPointsDistributed' => (int) (clone $collected)->sum('total_points'),
            'totalWeight' => (float) (clone $collected)->sum('total_weight'),
            'approvedSaldoValue' => (float) PointExchange::where('reward_type', 'saldo')
                ->where('status', 'approved')
                ->sum('value'),
        ];

        $recentPickupRequests = PickupRequest::with(['resident', 'collector', 'wasteCategories'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentPickupRequests'));
    }
}
