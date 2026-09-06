<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRewardRequest;
use App\Http\Requests\Admin\UpdateRewardRequest;
use App\Models\Reward;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class RewardController extends Controller
{
    public function index(): View
    {
        return view('admin.rewards.index', [
            'rewards' => Reward::withCount('pointExchanges')->latest()->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.rewards.create');
    }

    public function store(StoreRewardRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeImage($request->file('image'), 'rewards');
        }

        Reward::create($data);

        return redirect()
            ->route('admin.rewards.index')
            ->with('success', 'Reward berhasil ditambahkan.');
    }

    public function edit(Reward $reward): View
    {
        return view('admin.rewards.edit', compact('reward'));
    }

    public function update(UpdateRewardRequest $request, Reward $reward): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($reward->image) {
                Storage::disk('public')->delete($reward->image);
            }
            $data['image'] = $this->storeImage($request->file('image'), 'rewards');
        }

        $reward->update($data);

        return redirect()
            ->route('admin.rewards.index')
            ->with('success', 'Reward berhasil diperbarui.');
    }

    public function destroy(Request $request, Reward $reward): RedirectResponse
    {
        if ($reward->pointExchanges()->where('status', 'pending')->exists()) {
            return back()->with('error', 'Reward ini masih memiliki penukaran yang menunggu diproses, tidak bisa dihapus.');
        }

        if ($reward->image) {
            Storage::disk('public')->delete($reward->image);
        }

        $reward->delete();

        return back()->with('success', 'Reward berhasil dihapus.');
    }

    private function storeImage($file, string $folder): string
    {
        return $file->store($folder, 'public');
    }
}