<?php

namespace Tests\Feature\Resident;

use App\Models\PickupRequest;
use App\Models\User;
use App\Models\WasteCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PickupRequestLifecycleTest extends TestCase
{
    use RefreshDatabase;

    public function test_resident_can_submit_pickup_request_with_categories(): void
    {
        $resident = User::factory()->resident()->create();
        $plastik = WasteCategory::factory()->create(['points_per_kg' => 10]);
        $kertas = WasteCategory::factory()->create(['points_per_kg' => 8]);

        $pickupDate = now()->addDays(2)->toDateString();

        $response = $this->actingAs($resident)->post(route('resident.pickup-requests.store'), [
            'address' => 'Jl. Merdeka No. 1',
            'pickup_date' => $pickupDate,
            'time_slot' => '08:00-10:00',
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
            'time_slot' => '08:00-10:00',
        ]);

        $pickupRequest = PickupRequest::first();
        $this->assertNotNull($pickupRequest->scheduled_at);
        $this->assertEquals($pickupDate, $pickupRequest->scheduled_at->toDateString());
        $this->assertEquals('08:00', $pickupRequest->scheduled_at->format('H:i'));
        $this->assertEquals(2, $pickupRequest->wasteCategories()->count());
        $this->assertEquals(2.5, $pickupRequest->wasteCategories()->whereKey($plastik->id)->first()->pivot->estimated_weight);
    }

    public function test_resident_cannot_submit_without_pickup_date_and_slot(): void
    {
        $resident = User::factory()->resident()->create();
        $plastik = WasteCategory::factory()->create();

        $response = $this->actingAs($resident)->post(route('resident.pickup-requests.store'), [
            'address' => 'Jl. Merdeka No. 1',
            'categories' => [$plastik->id],
            'estimated_weight' => [$plastik->id => 2.5],
        ]);

        $response->assertSessionHasErrors(['pickup_date', 'time_slot']);
        $this->assertDatabaseCount('pickup_requests', 0);
        $this->assertDatabaseCount('pickup_request_waste_category', 0);
    }

    public function test_resident_cannot_submit_on_past_date(): void
    {
        $resident = User::factory()->resident()->create();
        $plastik = WasteCategory::factory()->create();

        $response = $this->actingAs($resident)->post(route('resident.pickup-requests.store'), [
            'address' => 'Jl. Merdeka No. 1',
            'pickup_date' => now()->subDay()->toDateString(),
            'time_slot' => '08:00-10:00',
            'categories' => [$plastik->id],
            'estimated_weight' => [$plastik->id => 2.5],
        ]);

        $response->assertSessionHasErrors('pickup_date');
        $this->assertDatabaseCount('pickup_requests', 0);
    }

    public function test_resident_cannot_submit_with_invalid_slot(): void
    {
        $resident = User::factory()->resident()->create();
        $plastik = WasteCategory::factory()->create();

        $response = $this->actingAs($resident)->post(route('resident.pickup-requests.store'), [
            'address' => 'Jl. Merdeka No. 1',
            'pickup_date' => now()->addDays(2)->toDateString(),
            'time_slot' => '99:00-99:00',
            'categories' => [$plastik->id],
            'estimated_weight' => [$plastik->id => 2.5],
        ]);

        $response->assertSessionHasErrors('time_slot');
        $this->assertDatabaseCount('pickup_requests', 0);
    }

    public function test_resident_cannot_submit_when_slot_is_full(): void
    {
        $resident = User::factory()->resident()->create();
        $plastik = WasteCategory::factory()->create();
        $pickupDate = now()->addDays(2)->toDateString();

        PickupRequest::factory()->count(PickupRequest::MAX_REQUESTS_PER_SLOT)->create([
            'status' => 'pending',
            'scheduled_at' => Carbon::parse($pickupDate.' 08:00:00'),
            'time_slot' => '08:00-10:00',
        ]);

        $response = $this->actingAs($resident)->post(route('resident.pickup-requests.store'), [
            'address' => 'Jl. Merdeka No. 1',
            'pickup_date' => $pickupDate,
            'time_slot' => '08:00-10:00',
            'categories' => [$plastik->id],
            'estimated_weight' => [$plastik->id => 2.5],
        ]);

        $response->assertSessionHasErrors('time_slot');
        $this->assertDatabaseCount('pickup_requests', PickupRequest::MAX_REQUESTS_PER_SLOT);
    }

    public function test_resident_cannot_submit_pickup_without_categories(): void
    {
        $resident = User::factory()->resident()->create();

        $response = $this->actingAs($resident)->post(route('resident.pickup-requests.store'), [
            'address' => 'Jl. Merdeka No. 1',
            'pickup_date' => now()->addDays(2)->toDateString(),
            'time_slot' => '08:00-10:00',
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
