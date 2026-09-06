<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom role (Resident, Admin, Collector) sesuai actor
     * pada use case diagram TemJi, serta profil tambahan (phone, address)
     * dan saldo poin milik Resident.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['resident', 'admin', 'collector'])
                  ->default('resident')
                  ->after('email');
            $table->string('phone')->nullable()->after('role');
            $table->string('address')->nullable()->after('phone');
            $table->unsignedInteger('points')->default(0)->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'address', 'points']);
        });
    }
};
