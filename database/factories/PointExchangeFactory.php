<?php

namespace Database\Factories;

use App\Models\Reward;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PointExchange>
 */
class PointExchangeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'reward_id' => Reward::factory(),
            'points_used' => fake()->numberBetween(50, 300),
            'status' => 'pending',
        ];
    }

    public function pending(): static
    {
        return $this->state(fn () => ['status' => 'pending']);
    }

    public function approved(): static
    {
        return $this->state(fn () => ['status' => 'approved']);
    }
}