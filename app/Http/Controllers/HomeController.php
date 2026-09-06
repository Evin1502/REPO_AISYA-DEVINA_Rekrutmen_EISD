<?php

namespace App\Http\Controllers;

use App\Models\PickupRequest;
use App\Models\Reward;
use App\Models\User;
use App\Models\WasteCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user) {
            $route = match ($user->role) {
                'admin' => 'admin.dashboard',
                'collector' => 'collector.dashboard',
                default => 'resident.dashboard',
            };

            return redirect()->route($route);
        }

        $wasteCategories = WasteCategory::orderByDesc('points_per_kg')->get();
        $rewards = Reward::where('stock', '>', 0)->orderBy('points_required')->take(4)->get();

        $collected = PickupRequest::where('status', 'collected');

        $stats = [
            'total_weight' => (clone $collected)->sum('total_weight'),
            'total_points' => (clone $collected)->sum('total_points'),
            'residents' => User::where('role', 'resident')->count(),
            'collectors' => User::where('role', 'collector')->count(),
        ];

        return view('home', compact('wasteCategories', 'rewards', 'stats'));
    }
}