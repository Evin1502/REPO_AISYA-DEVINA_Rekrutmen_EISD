<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin TemJi',
            'email' => 'admin@temji.test',
            // Otomatis di-hash oleh cast 'hashed' pada Model User.
            'password' => 'admin12345',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
    }
}
