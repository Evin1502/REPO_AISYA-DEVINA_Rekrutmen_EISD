<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Slot waktu penjemputan dipilih Resident dari slot yang ditentukan sistem
     * (lihat PickupRequest::TIME_SLOTS). Disimpan sebagai key slot, mis. "08:00-10:00",
     * supaya pengecekan ketersediaan slot bisa dilakukan konsisten di seluruh role.
     */
    public function up(): void
    {
        Schema::table('pickup_requests', function (Blueprint $table) {
            $table->string('time_slot', 20)->nullable()->after('scheduled_at');
        });
    }

    public function down(): void
    {
        Schema::table('pickup_requests', function (Blueprint $table) {
            $table->dropColumn('time_slot');
        });
    }
};
