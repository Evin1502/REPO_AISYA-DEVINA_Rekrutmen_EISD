<?php

namespace App\Policies;

use App\Models\PickupRequest;
use App\Models\User;

class PickupRequestPolicy
{
    public function view(User $user, PickupRequest $pickupRequest): bool
    {
        return $user->isAdmin()
            || $pickupRequest->user_id === $user->id
            || $pickupRequest->collector_id === $user->id;
    }

    public function approve(User $user): bool
    {
        return $user->isAdmin();
    }

    public function reject(User $user): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, PickupRequest $pickupRequest): bool
    {
        return $user->isResident() && $pickupRequest->user_id === $user->id;
    }

    public function updateStatus(User $user, PickupRequest $pickupRequest): bool
    {
        return $user->isCollector() && $pickupRequest->collector_id === $user->id;
    }
}