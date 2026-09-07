<?php

namespace Tests\Feature\Resident;

use App\Models\Reward;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RewardPageTest extends TestCase
{
    use RefreshDatabase;

    private function resident(): User
    {
        return User::factory()->resident()->create(['points' => 500]);
    }

    public function test_rewards_index_shows_both_category_entries(): void
    {
        Reward::factory()->saldo(10000, 30)->create();
        Reward::factory()->barang()->create(['name' => 'Tumbler Custom', 'points_required' => 120, 'stock' => 5]);

        $this->actingAs($this->resident())
            ->get(route('resident.rewards.index'))
            ->assertOk()
            ->assertSee('Saldo / Cash Balance')
            ->assertSee('Barang');
    }

    public function test_saldo_page_only_shows_saldo_rewards(): void
    {
        $saldo = Reward::factory()->saldo(10000, 30)->create(['name' => 'Saldo Cash Rp10.000']);
        $barang = Reward::factory()->barang()->create(['name' => 'Tumbler Custom', 'points_required' => 120, 'stock' => 5]);

        $response = $this->actingAs($this->resident())
            ->get(route('resident.rewards.saldo'))
            ->assertOk();

        $response->assertSee($saldo->name)
            ->assertDontSee($barang->name);
    }

    public function test_barang_page_only_shows_barang_rewards(): void
    {
        $saldo = Reward::factory()->saldo(10000, 30)->create(['name' => 'Saldo Cash Rp10.000']);
        $barang = Reward::factory()->barang()->create(['name' => 'Tumbler Custom', 'points_required' => 120, 'stock' => 5]);

        $response = $this->actingAs($this->resident())
            ->get(route('resident.rewards.barang'))
            ->assertOk();

        $response->assertSee($barang->name)
            ->assertDontSee($saldo->name);
    }

    public function test_barang_page_is_limited_to_three_items(): void
    {
        foreach (['Produk A', 'Produk B', 'Produk C', 'Produk D'] as $name) {
            Reward::factory()->barang()->create(['name' => $name, 'points_required' => 100, 'stock' => 5]);
        }

        $response = $this->actingAs($this->resident())->get(route('resident.rewards.barang'))->assertOk();

        $response->assertSee('Produk A')
            ->assertSee('Produk B')
            ->assertSee('Produk C')
            ->assertDontSee('Produk D');
    }

    public function test_barang_page_still_shows_cash_balance(): void
    {
        $resident = User::factory()->resident()->create(['points' => 500, 'cash_balance' => 50000]);
        Reward::factory()->barang()->create(['name' => 'Tumbler Custom', 'points_required' => 120, 'stock' => 5]);

        $this->actingAs($resident)
            ->get(route('resident.rewards.barang'))
            ->assertOk()
            ->assertSee('Rp50.000');
    }
}
