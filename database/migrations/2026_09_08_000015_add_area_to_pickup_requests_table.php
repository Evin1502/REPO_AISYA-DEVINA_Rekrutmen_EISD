<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom `area` (kelurahan/kecamatan) TERPISAH dari `address` (alamat detail bebas).
     *
     * Kenapa perlu dipisah: `address` adalah teks bebas (mis. "Jl. Cihapit No. 42,
     * RT 03/RW 05") yang tidak bisa diagregasi/dikelompokkan secara andal karena
     * variasi penulisannya tidak terbatas. `area` dipilih dari daftar tetap (lihat
     * StorePickupRequestRequest) supaya bisa di-groupBy untuk laporan skala kota
     * (breakdown volume sampah & ketepatan waktu per wilayah) -- lihat
     * Admin\DashboardController.
     */
    public function up(): void
    {
        Schema::table('pickup_requests', function (Blueprint $table) {
            $table->string('area')->nullable()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('pickup_requests', function (Blueprint $table) {
            $table->dropColumn('area');
        });
    }
};
