<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WasteCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PickupRequest>
 */
class PickupRequestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'collector_id' => null,
            'address' => fake()->address(),
            'area' => fake()->randomElement(config('temji.service_areas')),
            'status' => 'pending',
            'scheduled_at' => null,
            'total_weight' => null,
            'total_points' => null,
            'notes' => null,
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

    public function scheduled(): static
    {
        return $this->state(fn () => ['status' => 'scheduled', 'scheduled_at' => now()]);
    }

    public function collected(): static
    {
        return $this->state(fn () => ['status' => 'collected', 'scheduled_at' => now()]);
    }

    public function rejected(): static
    {
        return $this->state(fn () => ['status' => 'rejected']);
    }
}