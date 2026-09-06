<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_resident_cannot_access_admin_pages(): void
    {
        $resident = User::factory()->resident()->create();
        $this->actingAs($resident);

        $this->get(route('admin.dashboard'))->assertForbidden();
        $this->get(route('admin.waste-categories.index'))->assertForbidden();
        $this->get(route('admin.users.index'))->assertForbidden();
    }

    public function test_resident_cannot_access_collector_pages(): void
    {
        $resident = User::factory()->resident()->create();
        $this->actingAs($resident);

        $this->get(route('collector.dashboard'))->assertForbidden();
        $this->get(route('collector.pickup-requests.index'))->assertForbidden();
    }

    public function test_admin_cannot_access_resident_pages(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        $this->get(route('resident.dashboard'))->assertForbidden();
        $this->get(route('resident.pickup-requests.index'))->assertForbidden();
    }

    public function test_admin_cannot_access_collector_pages(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        $this->get(route('collector.dashboard'))->assertForbidden();
    }

    public function test_collector_cannot_access_admin_pages(): void
    {
        $collector = User::factory()->collector()->create();
        $this->actingAs($collector);

        $this->get(route('admin.dashboard'))->assertForbidden();
        $this->get(route('admin.point-exchanges.index'))->assertForbidden();
    }

    public function test_collector_cannot_access_resident_pages(): void
    {
        $collector = User::factory()->collector()->create();
        $this->actingAs($collector);

        $this->get(route('resident.dashboard'))->assertForbidden();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get(route('collector.dashboard'))->assertRedirect(route('login'));
        $this->get(route('resident.dashboard'))->assertRedirect(route('login'));
    }
}