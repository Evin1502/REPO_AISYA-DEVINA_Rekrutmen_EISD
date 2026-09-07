<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $activePickup = $user->pickupRequests()
            ->with(['wasteCategories', 'collector'])
            ->whereIn('status', ['pending', 'approved', 'scheduled'])
            ->latest()
            ->first();

        $recentPickups = $user->pickupRequests()
            ->with('wasteCategories')
            ->latest()
            ->limit(4)
            ->get();

        $lastCollected = $user->pickupRequests()
            ->where('status', 'collected')
            ->latest('updated_at')
            ->first();

        $data = [
            'pointsBalance' => $user->points,
            'cashBalanceLabel' => $user->cashBalanceLabel(),
            'pendingPickups' => $user->pickupRequests()->where('status', 'pending')->count(),
            'unreadNotifications' => $user->unreadNotificationsCount(),
            'recentHistories' => $user->pointHistories()
                ->with('pickupRequest')
                ->latest()
                ->limit(5)
                ->get(),
            'activePickup' => $activePickup,
            'recentPickups' => $recentPickups,
            'featuredReward' => Reward::where('stock', '>', 0)->orderBy('points_required')->first(),
            'collectedWeight' => (float) $user->pickupRequests()->where('status', 'collected')->sum('total_weight'),
            'lastCollected' => $lastCollected,
        ];

        return view('resident.dashboard', $data);
    }
}
