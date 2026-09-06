<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RewardController extends Controller
{
    /**
     * Use case: "Melakukan penukaran poin" (Resident) - katalog reward yang tersedia.
     */
    public function index(Request $request): View
    {
        $rewards = Reward::where('stock', '>', 0)
            ->orderBy('points_required')
            ->paginate(9);

        $user = $request->user();

        return view('resident.rewards.index', compact('rewards', 'user'));
    }
}
