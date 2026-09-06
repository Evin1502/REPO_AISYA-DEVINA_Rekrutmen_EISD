<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreWasteCategoryRequest;
use App\Http\Requests\Admin\UpdateWasteCategoryRequest;
use App\Models\WasteCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WasteCategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.waste-categories.index', [
            'categories' => WasteCategory::withCount('pickupRequests')->latest()->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.waste-categories.create');
    }

    public function store(StoreWasteCategoryRequest $request): RedirectResponse
    {
        WasteCategory::create($request->validated());

        return redirect()
            ->route('admin.waste-categories.index')
            ->with('success', 'Kategori sampah berhasil ditambahkan.');
    }

    public function edit(WasteCategory $wasteCategory): View
    {
        return view('admin.waste-categories.edit', compact('wasteCategory'));
    }

    public function update(UpdateWasteCategoryRequest $request, WasteCategory $wasteCategory): RedirectResponse
    {
        $wasteCategory->update($request->validated());

        return redirect()
            ->route('admin.waste-categories.index')
            ->with('success', 'Kategori sampah berhasil diperbarui.');
    }

    public function destroy(Request $request, WasteCategory $wasteCategory): RedirectResponse
    {
        $usedInActive = $wasteCategory->pickupRequests()
            ->whereIn('status', ['pending', 'approved', 'scheduled'])
            ->exists();

        if ($usedInActive) {
            return back()->with('error', 'Kategori ini masih dipakai pada pengajuan aktif, tidak bisa dihapus.');
        }

        $wasteCategory->pickupRequests()->detach();
        $wasteCategory->delete();

        return back()->with('success', 'Kategori sampah berhasil dihapus.');
    }
}