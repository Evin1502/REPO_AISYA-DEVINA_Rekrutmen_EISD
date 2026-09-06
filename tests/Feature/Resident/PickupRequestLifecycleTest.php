<?php

namespace Tests\Feature\Resident;

use App\Models\PickupRequest;
use App\Models\User;
use App\Models\WasteCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PickupRequestLifecycleTest extends TestCase
{
    use RefreshDatabase;

    public function test_resident_can_submit_pickup_request_with_categories(): void
    {
        $resident = User::factory()->resident()->create();
        $plastik = WasteCategory::factory()->create(['points_per_kg' => 10]);
        $kertas = WasteCategory::factory()->create(['points_per_kg' => 8]);

        $response = $this->actingAs($resident)->post(route('resident.pickup-requests.store'), [
            'address' => 'Jl. Merdeka No. 1',
            'notes' => 'Tolong ambil di gerbang.',
            'categories' => [$plastik->id, $kertas->id],
            'estimated_weight' => [
                $plastik->id => 2.5,
                $kertas->id => 1.5,
            ],
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('pickup_requests', [
            'user_id' => $resident->id,
            'address' => 'Jl. Merdeka No. 1',
            'status' => 'pending',
        ]);

        $pickupRequest = PickupRequest::first();
        $this->assertEquals(2, $pickupRequest->wasteCategories()->count());
        $this->assertEquals(2.5, $pickupRequest->wasteCategories()->whereKey($plastik->id)->first()->pivot->estimated_weight);
    }

    public function test_resident_cannot_submit_pickup_without_categories(): void
    {
        $resident = User::factory()->resident()->create();

        $response = $this->actingAs($resident)->post(route('resident.pickup-requests.store'), [
            'address' => 'Jl. Merdeka No. 1',
            'categories' => [],
            'estimated_weight' => [],
        ]);

        $response->assertSessionHasErrors('categories');
        $this->assertDatabaseCount('pickup_requests', 0);
    }

    public function test_resident_can_cancel_pending_request(): void
    {
        $resident = User::factory()->resident()->create();
        $pickupRequest = PickupRequest::factory()->pending()->create(['user_id' => $resident->id]);

        $response = $this->actingAs($resident)->delete(route('resident.pickup-requests.destroy', $pickupRequest));

        $response->assertRedirect();
        $this->assertDatabaseMissing('pickup_requests', ['id' => $pickupRequest->id]);
    }

    public function test_resident_cannot_cancel_approved_request(): void
    {
        $resident = User::factory()->resident()->create();
        $pickupRequest = PickupRequest::factory()->approved()->create(['user_id' => $resident->id]);

        $response = $this->actingAs($resident)->delete(route('resident.pickup-requests.destroy', $pickupRequest));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('pickup_requests', ['id' => $pickupRequest->id]);
    }

    public function test_resident_cannot_cancel_collected_request(): void
    {
        $resident = User::factory()->resident()->create();
        $pickupRequest = PickupRequest::factory()->collected()->create(['user_id' => $resident->id]);

        $response = $this->actingAs($resident)->delete(route('resident.pickup-requests.destroy', $pickupRequest));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('pickup_requests', ['id' => $pickupRequest->id]);
    }

    public function test_resident_cannot_view_other_residents_request(): void
    {
        $owner = User::factory()->resident()->create();
        $other = User::factory()->resident()->create();
        $pickupRequest = PickupRequest::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($other)->get(route('resident.pickup-requests.show', $pickupRequest))->assertForbidden();
    }
}