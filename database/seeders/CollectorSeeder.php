<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class CollectorSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Collector 1',
            'email' => 'collector1@temji.test',
            'password' => 'collector12345',
            'role' => 'collector',
            'email_verified_at' => now(),
        ]);
    }
}
