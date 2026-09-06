<?php

namespace App\Http\Controllers\Collector;

use App\Http\Controllers\Controller;
use App\Models\PickupRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $stats = [
            'assigned' => $request->user()->assignedPickups()
                ->whereIn('status', ['approved', 'scheduled'])
                ->count(),
            'scheduledToday' => $request->user()->assignedPickups()
                ->where('status', 'scheduled')
                ->whereDate('scheduled_at', Carbon::today())
                ->count(),
            'collected' => $request->user()->assignedPickups()
                ->where('status', 'collected')
                ->count(),
        ];

        $queue = $request->user()->assignedPickups()
            ->with(['resident', 'wasteCategories'])
            ->whereIn('status', ['approved', 'scheduled'])
            ->latest()
            ->limit(10)
            ->get();

        return view('collector.dashboard', compact('stats', 'queue'));
    }
}