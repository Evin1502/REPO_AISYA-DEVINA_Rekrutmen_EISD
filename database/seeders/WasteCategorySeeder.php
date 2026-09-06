<?php

namespace Database\Seeders;

use App\Models\WasteCategory;
use Illuminate\Database\Seeder;

class WasteCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Plastik', 'description' => 'Botol plastik, kemasan plastik, dan plastik lainnya.', 'points_per_kg' => 10],
            ['name' => 'Kertas', 'description' => 'Koran, kardus, buku, dan kertas bekas lainnya.', 'points_per_kg' => 8],
            ['name' => 'Logam', 'description' => 'Kaleng, besi bekas, alumunium, dan logam lainnya.', 'points_per_kg' => 15],
            ['name' => 'Kaca', 'description' => 'Botol kaca, gelas kaca, dan pecahan kaca.', 'points_per_kg' => 5],
            ['name' => 'Organik', 'description' => 'Sisa makanan, daun, dan sampah organik lainnya.', 'points_per_kg' => 3],
        ];

        foreach ($categories as $category) {
            WasteCategory::create($category);
        }
    }
}