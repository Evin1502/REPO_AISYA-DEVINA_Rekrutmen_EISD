<?php

namespace App\Http\Controllers\Collector;

use App\Http\Controllers\Controller;
use App\Http\Requests\Collector\UpdatePickupRequest;
use App\Models\AppNotification;
use App\Models\PickupRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PickupRequestController extends Controller
{
    public function index(Request $request): View
    {
        $pickupRequests = $request->user()
            ->assignedPickups()
            ->with(['resident', 'wasteCategories'])
            ->whereIn('status', ['approved', 'scheduled'])
            ->latest()
            ->paginate(10);

        return view('collector.pickup-requests.index', compact('pickupRequests'));
    }

    public function show(PickupRequest $pickupRequest): View
    {
        $this->authorize('view', $pickupRequest);

        $pickupRequest->load(['resident', 'wasteCategories', 'pointHistories']);

        return view('collector.pickup-requests.show', compact('pickupRequest'));
    }

    public function updateStatus(UpdatePickupRequest $request, PickupRequest $pickupRequest): RedirectResponse
    {
        $this->authorize('updateStatus', $pickupRequest);

        if ($pickupRequest->status !== 'approved') {
            return back()->with('error', 'Status pengajuan tidak valid untuk tindakan ini.');
        }

        $validated = $request->validated();

        DB::transaction(function () use ($pickupRequest, $validated) {
            $totalWeight = 0.0;
            $totalPoints = 0;

            foreach ($pickupRequest->wasteCategories as $category) {
                $weight = (float) ($validated['actual_weight'][$category->id] ?? 0);

                $pickupRequest->wasteCategories()
                    ->updateExistingPivot($category->id, ['actual_weight' => $weight]);

                $totalWeight += $weight;
                $totalPoints += (int) round($weight * $category->points_per_kg);
            }

            $pickupRequest->update([
                'status' => 'collected',
                'total_weight' => $totalWeight,
                'total_points' => $totalPoints,
            ]);

            $resident = $pickupRequest->resident;
            $resident->increment('points', $totalPoints);

            $resident->pointHistories()->create([
                'pickup_request_id' => $pickupRequest->id,
                'points' => $totalPoints,
                'type' => 'earn',
                'description' => 'Poin dari penjemputan sampah #' . $pickupRequest->id,
            ]);

            AppNotification::create([
                'user_id' => $resident->id,
                'title' => 'Penjemputan Selesai',
                'message' => "Penjemputan sampah #{$pickupRequest->id} selesai! Anda mendapat {$totalPoints} poin.",
            ]);
        });

        return redirect()
            ->route('collector.pickup-requests.index')
            ->with('success', 'Penjemputan selesai. Poin sudah ditambahkan ke saldo resident.');
    }

    public function history(Request $request): View
    {
        $pickupRequests = $request->user()
            ->assignedPickups()
            ->with(['resident', 'wasteCategories'])
            ->where('status', 'collected')
            ->latest()
            ->paginate(10);

        return view('collector.history', compact('pickupRequests'));
    }
}