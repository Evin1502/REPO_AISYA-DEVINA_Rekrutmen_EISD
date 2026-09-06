<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\AppNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Use case: "Menerima notifikasi atau berita terbaru" (Resident).
     */
    public function index(Request $request): View
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->paginate(15);

        return view('resident.notifications.index', compact('notifications'));
    }

    public function markAsRead(AppNotification $notification): RedirectResponse
    {
        $this->authorize('markAsRead', $notification);

        $notification->update(['is_read' => true]);

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }
}
