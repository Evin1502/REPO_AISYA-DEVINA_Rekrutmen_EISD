<?php

namespace Database\Seeders;

use App\Models\Reward;
use Illuminate\Database\Seeder;

class RewardSeeder extends Seeder
{
    public function run(): void
    {
        $rewards = collect($this->saldoTiers())
            ->merge($this->barang())
            ->all();

        foreach ($rewards as $reward) {
            Reward::updateOrCreate(
                ['name' => $reward['name']],
                $reward
            );
        }
    }

    private function saldoTiers(): array
    {
        $tiers = [];
        $maxNominal = 100000;
        $step = 5000;
        $basePoints = 10;

        for ($nominal = $step; $nominal <= $maxNominal; $nominal += $step) {
            $points = ($nominal / $step) * 10 + $basePoints;

            $tiers[] = [
                'name' => 'Saldo Cash Rp'.number_format($nominal, 0, ',', '.'),
                'category' => Reward::CATEGORY_SALDO,
                'nominal' => $nominal,
                'description' => 'Saldo / cash balance senilai Rp'.number_format($nominal, 0, ',', '.').', diproses manual oleh admin (simulasi).',
                'points_required' => $points,
                'stock' => 999999,
            ];
        }

        return $tiers;
    }

    private function barang(): array
    {
        return [
            [
                'name' => 'Tumbler Custom Branded',
                'category' => Reward::CATEGORY_BARANG,
                'nominal' => null,
                'description' => 'Tumbler custom dengan logo TemJi, cocok untuk gaya hidup ramah lingkungan.',
                'points_required' => 120,
                'stock' => 20,
            ],
            [
                'name' => 'Kaos Eksklusif (Official Merchandise)',
                'category' => Reward::CATEGORY_BARANG,
                'nominal' => null,
                'description' => 'Kaos merchandise resmi TemJi, bahan katun nyaman, tersedia ukuran S-XL.',
                'points_required' => 170,
                'stock' => 15,
            ],
            [
                'name' => 'Power Bank 10.000 mAh',
                'category' => Reward::CATEGORY_BARANG,
                'nominal' => null,
                'description' => 'Power bank kapasitas 10.000 mAh untuk kebutuhan daya perangkat sehari-hari.',
                'points_required' => 500,
                'stock' => 10,
            ],
        ];
    }
}
