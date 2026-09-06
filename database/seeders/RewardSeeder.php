<?php

namespace Database\Seeders;

use App\Models\Reward;
use Illuminate\Database\Seeder;

class RewardSeeder extends Seeder
{
    public function run(): void
    {
        $rewards = [
            [
                'name' => 'Voucher Belanja Rp 25.000',
                'description' => 'Voucher belanja senilai Rp 25.000 yang dapat digunakan di toko mitra TemJi.',
                'points_required' => 150,
                'stock' => 20,
            ],
            [
                'name' => 'Tas Belanja Ramah Lingkungan',
                'description' => 'Tas belanja kain yang dapat dipakai ulang untuk mengurangi sampah plastik.',
                'points_required' => 80,
                'stock' => 30,
            ],
            [
                'name' => 'Botol Minum Stainless',
                'description' => 'Botol minum stainless steel tahan lama untuk gaya hidup bebas plastik sekali pakai.',
                'points_required' => 200,
                'stock' => 10,
            ],
            [
                'name' => 'Paket Beras 5 kg',
                'description' => 'Paket beras premium 5 kg untuk kebutuhan pangan keluarga.',
                'points_required' => 250,
                'stock' => 15,
            ],
        ];

        foreach ($rewards as $reward) {
            Reward::create($reward);
        }
    }
}