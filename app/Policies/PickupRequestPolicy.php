<?php

namespace App\Policies;

use App\Models\PickupRequest;
use App\Models\User;

class PickupRequestPolicy
{
    public function view(User $user, PickupRequest $pickupRequest): bool
    {
        $isOwner = $pickupRequest->user_id === $user->id;
        $isAssignedCollector = $pickupRequest->collector_id === $user->id;

        return $user->isAdmin() || $isOwner || $isAssignedCollector;
    }

    public function cancel(User $user, PickupRequest $pickupRequest): bool
    {
        return $user->isResident()
            && $pickupRequest->user_id === $user->id
            && $pickupRequest->status === 'pending';
    }

    public function approve(User $user): bool
    {
        return $user->isAdmin();
    }

    public function assign(User $user, PickupRequest $pickupRequest): bool
    {
        return $user->isAdmin() && in_array($pickupRequest->status, ['pending', 'approved'], true);
    }

    public function updateStatus(User $user, PickupRequest $pickupRequest): bool
    {
        return $user->isCollector() && $pickupRequest->collector_id === $user->id;
    }
}