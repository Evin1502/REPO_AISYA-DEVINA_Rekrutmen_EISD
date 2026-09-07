<?php

namespace Database\Factories;

use App\Models\Reward;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reward>
 */
class RewardFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(3, true),
            'description' => fake()->sentence(),
            'category' => Reward::CATEGORY_BARANG,
            'nominal' => null,
            'points_required' => fake()->numberBetween(50, 300),
            'stock' => fake()->numberBetween(1, 50),
            'image' => null,
        ];
    }

    public function saldo(int $nominal = 10000, int $points = 30): static
    {
        return $this->state(fn () => [
            'name' => 'Saldo Cash Rp'.number_format($nominal, 0, ',', '.'),
            'category' => Reward::CATEGORY_SALDO,
            'nominal' => $nominal,
            'points_required' => $points,
            'stock' => 999999,
        ]);
    }

    public function barang(): static
    {
        return $this->state(fn () => [
            'category' => Reward::CATEGORY_BARANG,
            'nominal' => null,
        ]);
    }
}
