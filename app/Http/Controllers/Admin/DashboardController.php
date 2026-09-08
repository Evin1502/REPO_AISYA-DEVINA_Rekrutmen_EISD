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
    private const ON_TIME_TOLERANCE_MINUTES = 60;

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
            'onTimeRate' => $this->calculateOnTimeRate(),
        ];

        $recentPickupRequests = PickupRequest::with(['resident', 'collector', 'wasteCategories'])
            ->latest()
            ->limit(10)
            ->get();

        $areaBreakdown = PickupRequest::where('status', 'collected')
            ->whereNotNull('area')
            ->selectRaw('area, COUNT(*) as total_pickups, COALESCE(SUM(total_weight), 0) as total_weight')
            ->groupBy('area')
            ->orderByDesc('total_weight')
            ->get();

        return view('admin.dashboard', compact('stats', 'recentPickupRequests', 'areaBreakdown'));
    }

    private function calculateOnTimeRate(): ?float
    {
        $collectedWithSchedule = PickupRequest::where('status', 'collected')
            ->whereNotNull('scheduled_at')
            ->get(['scheduled_at', 'updated_at']);

        if ($collectedWithSchedule->isEmpty()) {
            return null;
        }

        $onTimeCount = $collectedWithSchedule->filter(function (PickupRequest $pickup) {
            $deadline = $pickup->scheduled_at->copy()->addMinutes(self::ON_TIME_TOLERANCE_MINUTES);

            return $pickup->updated_at->lte($deadline);
        })->count();

        return round($onTimeCount / $collectedWithSchedule->count() * 100, 1);
    }
}
