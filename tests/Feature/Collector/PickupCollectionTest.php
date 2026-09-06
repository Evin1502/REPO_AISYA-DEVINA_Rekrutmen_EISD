<?php

namespace Tests\Feature\Collector;

use App\Models\AppNotification;
use App\Models\PickupRequest;
use App\Models\PointHistory;
use App\Models\User;
use App\Models\WasteCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PickupCollectionTest extends TestCase
{
    use RefreshDatabase;

    private function makeAssignedScheduled(): array
    {
        $collector = User::factory()->collector()->create();
        $resident = User::factory()->resident()->create();
        $plastik = WasteCategory::factory()->create(['points_per_kg' => 10]);
        $kertas = WasteCategory::factory()->create(['points_per_kg' => 8]);

        $pickupRequest = PickupRequest::factory()->scheduled()->create([
            'user_id' => $resident->id,
            'collector_id' => $collector->id,
        ]);

        $pickupRequest->wasteCategories()->attach([
            $plastik->id => ['estimated_weight' => 2.0],
            $kertas->id => ['estimated_weight' => 1.0],
        ]);

        return compact('collector', 'resident', 'plastik', 'kertas', 'pickupRequest');
    }

    public function test_collector_can_mark_pickup_as_scheduled(): void
    {
        $collector = User::factory()->collector()->create();
        $resident = User::factory()->resident()->create();
        $pickupRequest = PickupRequest::factory()->approved()->create([
            'user_id' => $resident->id,
            'collector_id' => $collector->id,
        ]);

        $response = $this->actingAs($collector)->patch(
            route('collector.pickup-requests.status', $pickupRequest),
            ['scheduled_at' => now()->addHours(2)->toDateTimeString()]
        );

        $response->assertSessionHas('success');

        $pickupRequest->refresh();
        $this->assertEquals('scheduled', $pickupRequest->status);
        $this->assertNotNull($pickupRequest->scheduled_at);
    }

    public function test_collector_can_collect_and_points_are_calculated(): void
    {
        $data = $this->makeAssignedScheduled();
        $plastik = $data['plastik'];
        $kertas = $data['kertas'];

        $response = $this->actingAs($data['collector'])->patch(
            route('collector.pickup-requests.status', $data['pickupRequest']),
            [
                'actual_weight' => [
                    $plastik->id => 3.0,  // 3.0 * 10 = 30 poin
                    $kertas->id => 2.0,   // 2.0 * 8  = 16 poin
                ],
            ]
        );

        $response->assertRedirect();

        $pickupRequest = $data['pickupRequest']->fresh();

        $this->assertEquals('collected', $pickupRequest->status);
        $this->assertEquals(5.0, (float) $pickupRequest->total_weight);
        $this->assertEquals(46, $pickupRequest->total_points);

        $pickupRequest->wasteCategories->each(function ($category) {
            $this->assertNotNull($category->pivot->actual_weight);
        });
    }

    public function test_collector_collection_increments_user_points(): void
    {
        $data = $this->makeAssignedScheduled();
        $plastik = $data['plastik'];
        $kertas = $data['kertas'];

        $this->actingAs($data['collector'])->patch(
            route('collector.pickup-requests.status', $data['pickupRequest']),
            [
                'actual_weight' => [
                    $plastik->id => 1.0,  // 10 poin
                    $kertas->id => 2.0,   // 16 poin
                ],
            ]
        );

        $this->assertEquals(26, $data['resident']->fresh()->points);
    }

    public function test_collector_collection_creates_point_history_and_notification(): void
    {
        $data = $this->makeAssignedScheduled();
        $plastik = $data['plastik'];
        $kertas = $data['kertas'];

        $this->actingAs($data['collector'])->patch(
            route('collector.pickup-requests.status', $data['pickupRequest']),
            [
                'actual_weight' => [
                    $plastik->id => 1.0,
                    $kertas->id => 1.0,
                ],
            ]
        );

        $this->assertDatabaseHas('point_histories', [
            'user_id' => $data['resident']->id,
            'pickup_request_id' => $data['pickupRequest']->id,
            'type' => 'earn',
            'points' => 18,
        ]);

        $this->assertDatabaseHas('app_notifications', [
            'user_id' => $data['resident']->id,
            'title' => 'Penjemputan Selesai',
            'is_read' => false,
        ]);
    }

    public function test_collector_cannot_process_unassigned_pickup(): void
    {
        $collectorA = User::factory()->collector()->create();
        $collectorB = User::factory()->collector()->create();
        $resident = User::factory()->resident()->create();
        $pickupRequest = PickupRequest::factory()->scheduled()->create([
            'user_id' => $resident->id,
            'collector_id' => $collectorA->id,
        ]);

        $this->actingAs($collectorB)
            ->get(route('collector.pickup-requests.show', $pickupRequest))
            ->assertForbidden();
    }

    public function test_collector_cannot_collect_without_actual_weights(): void
    {
        $data = $this->makeAssignedScheduled();

        $this->actingAs($data['collector'])->patch(
            route('collector.pickup-requests.status', $data['pickupRequest']),
            []
        )->assertSessionHasErrors('actual_weight');

        $this->assertEquals('scheduled', $data['pickupRequest']->fresh()->status);
    }
}