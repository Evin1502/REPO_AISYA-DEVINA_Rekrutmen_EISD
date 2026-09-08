<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Http\Requests\Resident\StorePickupRequestRequest;
use App\Models\PickupRequest;
use App\Models\WasteCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class PickupRequestController extends Controller
{
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
        $timeSlots = PickupRequest::timeSlots();
        $availability = PickupRequest::availabilityMap();
        $minDate = today()->toDateString();
        $maxDate = today()->addDays(PickupRequest::SLOT_WINDOW_DAYS - 1)->toDateString();
        $capacity = PickupRequest::MAX_REQUESTS_PER_SLOT;

        return view('resident.pickup-requests.create', compact(
            'wasteCategories',
            'timeSlots',
            'availability',
            'minDate',
            'maxDate',
            'capacity'
        ));
    }

    public function store(StorePickupRequestRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $slot = PickupRequest::slotByKey($validated['time_slot']);
        $scheduledAt = Carbon::parse($validated['pickup_date'].' '.$slot['start'].':00');

        $pickupRequest = $request->user()->pickupRequests()->create([
            'address' => $validated['address'],
            'area' => $validated['area'] ?? null,
            'scheduled_at' => $scheduledAt,
            'time_slot' => $validated['time_slot'],
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
            ->with('success', 'Data berhasil diajukan. Pengajuan Pengambilan Sampah akan diproses oleh Admin.');
    }

    public function show(PickupRequest $pickupRequest): View
    {
        $this->authorize('view', $pickupRequest);

        $pickupRequest->load(['wasteCategories', 'collector', 'pointHistories']);

        return view('resident.pickup-requests.show', compact('pickupRequest'));
    }

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
