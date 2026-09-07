<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PickupRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PickupRequestController extends Controller
{
    public function index(Request $request): View
    {
        $query = PickupRequest::with(['resident', 'collector', 'wasteCategories']);

        if ($request->filled('status') && in_array($request->status, ['pending', 'approved', 'scheduled', 'collected', 'rejected'], true)) {
            $query->where('status', $request->status);
        }

        return view('admin.pickup-requests.index', [
            'pickupRequests' => $query->latest()->paginate(10)->withQueryString(),
            'currentStatus' => $request->status,
        ]);
    }

    public function show(PickupRequest $pickupRequest): View
    {
        $pickupRequest->load(['resident', 'collector', 'wasteCategories', 'pointHistories']);

        $collectors = User::where('role', 'collector')->orderBy('name')->get();

        return view('admin.pickup-requests.show', compact('pickupRequest', 'collectors'));
    }

    public function approve(Request $request, PickupRequest $pickupRequest): RedirectResponse
    {
        $this->authorize('approve', $pickupRequest);

        abort_unless($pickupRequest->status === 'pending', 422, 'Hanya pengajuan berstatus pending yang bisa disetujui.');

        $validated = $request->validate([
            'collector_id' => ['required', 'exists:users,id'],
            'scheduled_at' => ['nullable', 'date'],
        ]);

        if (User::whereKey($validated['collector_id'])->where('role', 'collector')->doesntExist()) {
            return back()->with('error', 'Pilih kolektor yang valid.');
        }

        $pickupRequest->update([
            'status' => 'approved',
            'collector_id' => $validated['collector_id'],
            'scheduled_at' => $validated['scheduled_at'] ?? null,
        ]);

        return redirect()
            ->route('admin.pickup-requests.show', $pickupRequest)
            ->with('success', 'Pengajuan disetujui dan kolektor ditugaskan.');
    }

    public function reject(Request $request, PickupRequest $pickupRequest): RedirectResponse
    {
        $this->authorize('reject', $pickupRequest);

        abort_unless($pickupRequest->status === 'pending', 422, 'Hanya pengajuan berstatus pending yang bisa ditolak.');

        $pickupRequest->update(['status' => 'rejected']);

        return back()->with('success', 'Pengajuan ditolak.');
    }
}