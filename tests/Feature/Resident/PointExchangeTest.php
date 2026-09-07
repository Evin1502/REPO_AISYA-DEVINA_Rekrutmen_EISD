<?php

namespace Tests\Feature\Resident;

use App\Models\Reward;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PointExchangeTest extends TestCase
{
    use RefreshDatabase;

    public function test_resident_saldo_redeem_is_auto_approved_and_credits_cash_balance(): void
    {
        $resident = User::factory()->resident()->create(['points' => 100, 'cash_balance' => 0]);
        $reward = Reward::factory()->saldo(10000, 30)->create();

        $this->actingAs($resident)
            ->post(route('resident.rewards.exchange', $reward))
            ->assertRedirect(route('resident.point-exchanges.index'));

        $this->assertEquals(70, $resident->fresh()->points);
        $this->assertEquals('10000.00', $resident->fresh()->cash_balance);

        $this->assertDatabaseHas('point_exchanges', [
            'user_id' => $resident->id,
            'reward_id' => $reward->id,
            'reward_type' => 'saldo',
            'reward_name' => $reward->name,
            'value' => 10000,
            'points_used' => 30,
            'status' => 'approved',
        ]);

        $this->assertDatabaseHas('point_histories', [
            'user_id' => $resident->id,
            'points' => -30,
            'type' => 'redeem',
        ]);
    }

    public function test_resident_redeem_barang_decrements_stock(): void
    {
        $resident = User::factory()->resident()->create(['points' => 300]);
        $reward = Reward::factory()->barang()->create(['points_required' => 120, 'stock' => 5]);

        $this->actingAs($resident)
            ->post(route('resident.rewards.exchange', $reward))
            ->assertRedirect(route('resident.point-exchanges.index'));

        $this->assertEquals(4, $reward->fresh()->stock);
        $this->assertEquals(180, $resident->fresh()->points);

        $this->assertDatabaseHas('point_exchanges', [
            'user_id' => $resident->id,
            'reward_type' => 'barang',
            'value' => null,
            'points_used' => 120,
            'status' => 'pending',
        ]);
    }

    public function test_resident_cannot_redeem_when_points_are_insufficient(): void
    {
        $resident = User::factory()->resident()->create(['points' => 20]);
        $reward = Reward::factory()->saldo(10000, 30)->create();

        $this->actingAs($resident)
            ->post(route('resident.rewards.exchange', $reward))
            ->assertRedirect();

        $this->assertEquals(20, $resident->fresh()->points);
        $this->assertDatabaseCount('point_exchanges', 0);
        $this->assertDatabaseCount('point_histories', 0);
    }

    public function test_resident_cannot_redeem_when_stock_is_empty(): void
    {
        $resident = User::factory()->resident()->create(['points' => 500]);
        $reward = Reward::factory()->barang()->create(['points_required' => 120, 'stock' => 0]);

        $this->actingAs($resident)
            ->post(route('resident.rewards.exchange', $reward))
            ->assertRedirect();

        $this->assertEquals(500, $resident->fresh()->points);
        $this->assertDatabaseCount('point_exchanges', 0);
    }

    public function test_resident_can_see_their_exchange_history(): void
    {
        $resident = User::factory()->resident()->create(['points' => 100]);
        $reward = Reward::factory()->saldo(10000, 30)->create();

        $this->actingAs($resident)->post(route('resident.rewards.exchange', $reward));

        $this->actingAs($resident)
            ->get(route('resident.point-exchanges.index'))
            ->assertOk()
            ->assertSee($reward->name)
            ->assertSee('Saldo / Cash Balance')
            ->assertSee('Rp10.000');
    }
}
