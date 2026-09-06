<?php

namespace Tests\Feature\Admin;

use App\Models\PointExchange;
use App\Models\Reward;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PointExchangeTest extends TestCase
{
    use RefreshDatabase;

    private function makePendingExchange(): array
    {
        $admin = User::factory()->admin()->create();
        $resident = User::factory()->resident()->create(['points' => 500]);
        $reward = Reward::factory()->create(['points_required' => 100, 'stock' => 10]);

        $exchange = PointExchange::factory()->pending()->create([
            'user_id' => $resident->id,
            'reward_id' => $reward->id,
            'points_used' => 100,
        ]);

        return compact('admin', 'resident', 'reward', 'exchange');
    }

    public function test_admin_approve_changes_status_without_touching_points(): void
    {
        $data = $this->makePendingExchange();

        $this->actingAs($data['admin'])->patch(route('admin.point-exchanges.approve', $data['exchange']))->assertRedirect();

        $this->assertEquals('approved', $data['exchange']->fresh()->status);
        $this->assertEquals(500, $data['resident']->fresh()->points);
        $this->assertEquals(10, $data['reward']->fresh()->stock);
    }

    public function test_admin_reject_refunds_points_and_restores_stock(): void
    {
        $data = $this->makePendingExchange();

        $this->actingAs($data['admin'])->patch(route('admin.point-exchanges.reject', $data['exchange']))->assertRedirect();

        $this->assertEquals('rejected', $data['exchange']->fresh()->status);
        $this->assertEquals(600, $data['resident']->fresh()->points);
        $this->assertEquals(11, $data['reward']->fresh()->stock);
    }

    public function test_admin_reject_creates_refund_point_history(): void
    {
        $data = $this->makePendingExchange();

        $this->actingAs($data['admin'])->patch(route('admin.point-exchanges.reject', $data['exchange']));

        $this->assertDatabaseHas('point_histories', [
            'user_id' => $data['resident']->id,
            'points' => 100,
            'type' => 'refund',
        ]);
    }

    public function test_admin_cannot_approve_already_approved_exchange(): void
    {
        $admin = User::factory()->admin()->create();
        $resident = User::factory()->resident()->create(['points' => 400]);
        $reward = Reward::factory()->create(['points_required' => 100, 'stock' => 9]);
        $exchange = PointExchange::factory()
            ->approved()
            ->create(['user_id' => $resident->id, 'reward_id' => $reward->id, 'points_used' => 100]);

        $this->actingAs($admin)->patch(route('admin.point-exchanges.approve', $exchange))->assertStatus(422);
        $this->assertEquals('approved', $exchange->fresh()->status);
    }
}