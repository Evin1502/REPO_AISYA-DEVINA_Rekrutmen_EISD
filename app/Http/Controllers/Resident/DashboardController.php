<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

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
        ];

        return view('resident.dashboard', $data);
    }
}
