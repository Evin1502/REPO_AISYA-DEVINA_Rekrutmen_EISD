<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan akumulasi Saldo / Cash Balance milik resident.
     * Saldo bertambah saat Admin menyetujui penukaran poin kategori 'saldo'
     * (bersifat simulasi / pencatatan, belum terintegrasi payment gateway).
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('cash_balance', 12, 2)
                ->default(0)
                ->after('points')
                ->comment('Akumulasi saldo cash hasil penukaran poin yang telah disetujui');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('cash_balance');
        });
    }
};
