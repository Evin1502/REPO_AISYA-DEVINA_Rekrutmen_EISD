<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan pembeda kategori reward:
     * - 'saldo_pulsa' -> punya nominal fixed (Rp10.000 / 25.000 / 50.000 / 100.000)
     * - 'barang'      -> nominal null, cukup name + description
     */
    public function up(): void
    {
        Schema::table('rewards', function (Blueprint $table) {
            $table->enum('category', ['saldo_pulsa', 'barang'])
                ->default('barang')
                ->after('name');

            $table->decimal('nominal', 12, 2)
                ->nullable()
                ->after('category')
                ->comment('Diisi hanya untuk category = saldo_pulsa');
        });
    }

    public function down(): void
    {
        Schema::table('rewards', function (Blueprint $table) {
            $table->dropColumn(['category', 'nominal']);
        });
    }
};
