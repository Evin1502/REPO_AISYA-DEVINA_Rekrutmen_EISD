<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RewardController extends Controller
{
    public function index(Request $request): View
    {
        return view('resident.rewards.index', [
            'saldoCount' => Reward::where('category', Reward::CATEGORY_SALDO)->where('stock', '>', 0)->count(),
            'barangCount' => $this->barangRewards()->take(3)->get()->count(),
            'user' => $request->user(),
        ]);
    }

    public function saldo(Request $request): View
    {
        return $this->categoryPage($request, Reward::CATEGORY_SALDO);
    }

    public function barang(Request $request): View
    {
        $rewards = $this->barangRewards()->take(3)->get();

        return view('resident.rewards.category', [
            'rewards' => $rewards,
            'category' => Reward::CATEGORY_BARANG,
            'user' => $request->user(),
        ]);
    }

    private function categoryPage(Request $request, string $category): View
    {
        $rewards = Reward::where('category', $category)
            ->where('stock', '>', 0)
            ->orderBy('points_required')
            ->paginate(12);

        return view('resident.rewards.category', [
            'rewards' => $rewards,
            'category' => $category,
            'user' => $request->user(),
        ]);
    }

    private function barangRewards()
    {
        return Reward::where('category', Reward::CATEGORY_BARANG)
            ->where('stock', '>', 0)
            ->orderBy('points_required');
    }
}
