<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjadikan point_exchanges sebagai Redemption History Log yang mandiri:
     * tambahkan snapshot reward_type, reward_name, dan nominal (value) di baris
     * transaksi, sehingga riwayat tetap utuh meskipun data katalog reward berubah
     * atau dihapus di kemudian hari.
     */
    public function up(): void
    {
        Schema::table('point_exchanges', function (Blueprint $table) {
            $table->enum('reward_type', ['saldo', 'barang'])
                ->nullable()
                ->after('reward_id')
                ->comment('Saldo / Cash Balance atau barang fisik');

            $table->string('reward_name')->nullable()->after('reward_type');
            $table->decimal('value', 12, 2)->nullable()->after('reward_name')
                ->comment('Nominal rupiah (khusus saldo), null untuk barang');
        });

        DB::table('point_exchanges')
            ->select('id', 'reward_id')
            ->orderBy('id')
            ->chunkById(200, function ($exchanges) {
                foreach ($exchanges as $exchange) {
                    $reward = DB::table('rewards')->where('id', $exchange->reward_id)->first();

                    // Hanya snapshot kategori yang sudah valid (saldo/barang).
                    // Reward legacy (saldo_pulsa) akan dibersihkan oleh migration konversi kategori.
                    if (! $reward || ! in_array($reward->category, ['saldo', 'barang'], true)) {
                        continue;
                    }

                    DB::table('point_exchanges')
                        ->where('id', $exchange->id)
                        ->update([
                            'reward_type' => $reward->category,
                            'reward_name' => $reward->name,
                            'value' => $reward->nominal,
                        ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('point_exchanges', function (Blueprint $table) {
            $table->dropColumn(['reward_type', 'reward_name', 'value']);
        });
    }
};
