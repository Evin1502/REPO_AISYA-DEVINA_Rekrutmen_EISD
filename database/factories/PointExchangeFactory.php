<?php

namespace Database\Factories;

use App\Models\PointExchange;
use App\Models\Reward;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PointExchange>
 */
class PointExchangeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'reward_id' => Reward::factory(),
            'reward_type' => Reward::CATEGORY_BARANG,
            'reward_name' => fake()->words(3, true),
            'value' => null,
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
