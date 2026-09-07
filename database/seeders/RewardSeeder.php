<?php

namespace Database\Seeders;

use App\Models\Reward;
use Illuminate\Database\Seeder;

class RewardSeeder extends Seeder
{
    /**
     * Katalog reward modul Point Reward (build ulang).
     *
     * Jenis reward utama: Saldo / Cash Balance (bukan pulsa), dengan jenjang
     * bertingkat progresif dari Rp5.000 s.d. Rp100.000:
     *
     *   poin = (nominal / 5.000) * 10 + 10
     *
     * Contoh acuan: Rp5.000 = 20 poin, Rp10.000 = 30 poin, lalu naik 10 poin
     * per Rp5.000 hingga Rp100.000 = 210 poin.
     *
     * Dilengkapi 3 pilihan barang fisik. Penukaran bersifat simulasi/pencatatan
     * database (belum terintegrasi payment gateway / e-wallet otomatis).
     */
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

    /**
     * Jenjang saldo bertingkat: mulai Rp5.000 sampai Rp100.000, +Rp5.000 per tier.
     */
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

    /**
     * 3 pilihan barang fisik beserta estimasi poin seimbang dengan skala saldo
     * (kurs ±Rp300 per poin, memperhitungkan biaya pengadaan & logistik).
     */
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
