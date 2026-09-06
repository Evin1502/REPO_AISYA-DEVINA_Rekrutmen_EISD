<?php

namespace Tests\Feature\Admin;

use App\Models\PickupRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PickupRequestManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_approve_and_assign_collector(): void
    {
        $admin = User::factory()->admin()->create();
        $collector = User::factory()->collector()->create();
        $resident = User::factory()->resident()->create();
        $pickupRequest = PickupRequest::factory()->pending()->create(['user_id' => $resident->id]);

        $this->actingAs($admin)->patch(route('admin.pickup-requests.approve', $pickupRequest), [
            'collector_id' => $collector->id,
            'scheduled_at' => now()->addDay()->toDateTimeString(),
        ])->assertRedirect();

        $pickupRequest->refresh();

        $this->assertEquals('approved', $pickupRequest->status);
        $this->assertEquals($collector->id, $pickupRequest->collector_id);
        $this->assertNotNull($pickupRequest->scheduled_at);
    }

    public function test_admin_can_reject_pickup_request(): void
    {
        $admin = User::factory()->admin()->create();
        $resident = User::factory()->resident()->create();
        $pickupRequest = PickupRequest::factory()->pending()->create(['user_id' => $resident->id]);

        $this->actingAs($admin)->patch(route('admin.pickup-requests.reject', $pickupRequest))->assertRedirect();

        $this->assertEquals('rejected', $pickupRequest->fresh()->status);
    }

    public function test_admin_cannot_approve_collected_request(): void
    {
        $admin = User::factory()->admin()->create();
        $collector = User::factory()->collector()->create();
        $resident = User::factory()->resident()->create();
        $pickupRequest = PickupRequest::factory()->collected()->create([
            'user_id' => $resident->id,
            'collector_id' => $collector->id,
        ]);

        $this->actingAs($admin)->patch(route('admin.pickup-requests.approve', $pickupRequest), [
            'collector_id' => $collector->id,
        ])->assertStatus(422);

        $this->assertEquals('collected', $pickupRequest->fresh()->status);
    }

    public function test_admin_can_filter_pickup_requests_by_status(): void
    {
        $admin = User::factory()->admin()->create();
        PickupRequest::factory()->pending()->create(['user_id' => User::factory()->resident()]);
        PickupRequest::factory()->rejected()->create(['user_id' => User::factory()->resident()]);

        $response = $this->actingAs($admin)
            ->get(route('admin.pickup-requests.index', ['status' => 'rejected']))
            ->assertOk();

        $response->assertViewHas('pickupRequests', fn ($paginator) => $paginator->total() === 1);
    }
}