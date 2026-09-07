<?php

namespace Tests\Feature;

use App\Models\Reward;
use Database\Seeders\RewardSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RewardCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_full_saldo_ladder_and_three_items(): void
    {
        $this->seed(RewardSeeder::class);

        $this->assertDatabaseCount('rewards', 23); // 20 jenjang saldo + 3 barang

        $this->assertEquals(20, Reward::where('category', 'saldo')->count());
        $this->assertEquals(3, Reward::where('category', 'barang')->count());
    }

    public function test_saldo_ladder_points_follow_progressive_tiers(): void
    {
        $this->seed(RewardSeeder::class);

        $expected = [
            5000 => 20,
            10000 => 30,
            15000 => 40,
            20000 => 50,
            50000 => 110,
            100000 => 210,
        ];

        foreach ($expected as $nominal => $points) {
            $this->assertDatabaseHas('rewards', [
                'category' => 'saldo',
                'nominal' => $nominal,
                'points_required' => $points,
            ]);
        }
    }

    public function test_saldo_ladder_does_not_exceed_max_nominal(): void
    {
        $this->seed(RewardSeeder::class);

        $max = Reward::where('category', 'saldo')->max('nominal');
        $this->assertEquals(100000, (int) $max);
    }
}
