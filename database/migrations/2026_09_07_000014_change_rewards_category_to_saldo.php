<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Konversi kategori reward 'saldo_pulsa' -> 'saldo' (Saldo / Cash Balance).
     *
     * Dibuat sebagai migration tersendiri (bukan mengubah file 000011) supaya bekerja
     * baik di database yang sudah pernah migrate (enum lama) maupun database baru:
     *   1. perluas enum sementara (tambahkan 'saldo') agar semua nilai tetap valid,
     *   2. bersihkan baris legacy 'saldo_pulsa' yang tidak sesuai katalog baru,
     *   3. rapikan enum kembali hanya ['saldo', 'barang'].
     */
    public function up(): void
    {
        Schema::table('rewards', function (Blueprint $table) {
            $table->enum('category', ['saldo_pulsa', 'saldo', 'barang'])
                ->default('barang')
                ->change();
        });

        DB::table('rewards')
            ->where('category', 'saldo_pulsa')
            ->delete();

        Schema::table('rewards', function (Blueprint $table) {
            $table->enum('category', ['saldo', 'barang'])
                ->default('barang')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('rewards', function (Blueprint $table) {
            $table->enum('category', ['saldo', 'saldo_pulsa', 'barang'])
                ->default('barang')
                ->change();
        });

        DB::table('rewards')
            ->where('category', 'saldo')
            ->update(['category' => 'saldo_pulsa']);

        Schema::table('rewards', function (Blueprint $table) {
            $table->enum('category', ['saldo_pulsa', 'barang'])
                ->default('barang')
                ->change();
        });
    }
};
