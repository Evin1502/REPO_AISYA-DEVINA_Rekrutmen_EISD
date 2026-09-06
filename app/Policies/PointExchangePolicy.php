<?php

namespace App\Policies;

use App\Models\User;

class PointExchangePolicy
{
    public function approve(User $user): bool
    {
        return $user->isAdmin();
    }

    public function reject(User $user): bool
    {
        return $user->isAdmin();
    }
}