<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query();

        if ($request->filled('role') && in_array($request->role, ['resident', 'admin', 'collector'], true)) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        return view('admin.users.index', [
            'users' => $query->latest()->paginate(10)->withQueryString(),
            'currentRole' => $request->role,
            'search' => $request->search,
        ]);
    }

    public function show(User $user): View
    {
        $user->load([
            'pointHistories' => fn ($q) => $q->latest()->limit(15),
            'pickupRequests' => fn ($q) => $q->latest()->limit(10),
        ]);

        $totalEarned = (int) $user->pointHistories()->where('type', 'earn')->sum('points');
        $totalRedeemed = (int) $user->pointHistories()->where('type', 'redeem')->sum('points');

        return view('admin.users.show', compact('user', 'totalEarned', 'totalRedeemed'));
    }
}