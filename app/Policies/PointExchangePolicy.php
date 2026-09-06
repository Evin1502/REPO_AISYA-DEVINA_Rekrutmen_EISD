<?php

namespace App\Policies;

use App\Models\PointExchange;
use App\Models\User;

class PointExchangePolicy
{
    public function approve(User $user, PointExchange $pointExchange): bool
    {
        return $user->isAdmin() && $pointExchange->status === 'pending';
    }

    public function reject(User $user, PointExchange $pointExchange): bool
    {
        return $user->isAdmin() && $pointExchange->status === 'pending';
    }
}