<?php

namespace App\Policies;

use App\Models\AppNotification;
use App\Models\User;

class AppNotificationPolicy
{
    public function markAsRead(User $user, AppNotification $notification): bool
    {
        return $notification->user_id === $user->id;
    }
}