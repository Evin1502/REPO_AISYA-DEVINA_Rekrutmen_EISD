<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Http\Requests\Resident\StorePickupRequestRequest;
use App\Models\PickupRequest;
use App\Models\WasteCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PickupRequestController extends Controller
{
    /**
     * Use case: "Mengajukan pengambilan sampah" (Resident) - daftar pengajuan milik sendiri.
     */
    public function index(Request $request): View
    {
        $pickupRequests = $request->user()
            ->pickupRequests()
            ->with('wasteCategories')
            ->latest()
            ->paginate(10);

        return view('resident.pickup-requests.index', compact('pickupRequests'));
    }

    public function create(): View
    {
        $wasteCategories = WasteCategory::orderBy('name')->get();

        return view('resident.pickup-requests.create', compact('wasteCategories'));
    }

    /**
     * Use case: "Mengajukan pengambilan sampah" <<Include>> "Memilih kategori sampah".
     */
    public function store(StorePickupRequestRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $pickupRequest = $request->user()->pickupRequests()->create([
            'address' => $validated['address'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        $pivotData = [];
        foreach ($validated['categories'] as $categoryId) {
            $pivotData[$categoryId] = [
                'estimated_weight' => $validated['estimated_weight'][$categoryId] ?? null,
            ];
        }

        $pickupRequest->wasteCategories()->attach($pivotData);

        return redirect()
            ->route('resident.pickup-requests.show', $pickupRequest)
            ->with('success', 'Pengajuan pengambilan sampah berhasil dikirim! Menunggu persetujuan Admin.');
    }

    public function show(PickupRequest $pickupRequest): View
    {
        $this->authorize('view', $pickupRequest);

        $pickupRequest->load(['wasteCategories', 'collector', 'pointHistories']);

        return view('resident.pickup-requests.show', compact('pickupRequest'));
    }

    /**
     * Resident hanya boleh membatalkan pengajuan yang masih berstatus 'pending'.
     */
    public function destroy(PickupRequest $pickupRequest): RedirectResponse
    {
        $this->authorize('delete', $pickupRequest);

        if ($pickupRequest->status !== 'pending') {
            return back()->with('error', 'Pengajuan yang sudah diproses tidak bisa dibatalkan.');
        }

        $pickupRequest->wasteCategories()->detach();
        $pickupRequest->delete();

        return redirect()
            ->route('resident.pickup-requests.index')
            ->with('success', 'Pengajuan berhasil dibatalkan.');
    }
}
