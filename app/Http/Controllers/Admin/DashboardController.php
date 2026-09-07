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
        $stats = [
            'totalResidents' => User::where('role', 'resident')->count(),
            'pendingPickups' => PickupRequest::where('status', 'pending')->count(),
            'todayCollected' => PickupRequest::where('status', 'collected')
                ->whereDate('updated_at', Carbon::today())
                ->count(),
            'pendingExchanges' => PointExchange::where('status', 'pending')->count(),
            'totalPointsDistributed' => (int) PickupRequest::where('status', 'collected')->sum('total_points'),
            'approvedSaldoValue' => (float) PointExchange::where('reward_type', 'saldo')
                ->where('status', 'approved')
                ->sum('value'),
        ];

        $recentPickupRequests = PickupRequest::with(['resident', 'collector'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentPickupRequests'));
    }
}
