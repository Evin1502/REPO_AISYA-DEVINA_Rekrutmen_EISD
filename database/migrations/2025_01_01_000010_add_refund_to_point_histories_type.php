<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add 'refund' to point_histories.type so admin-rejected point exchanges
     * can record the refunded points in the resident's history.
     */
    public function up(): void
    {
        Schema::table('point_histories', function (Blueprint $table) {
            $table->enum('type', ['earn', 'redeem', 'refund'])->default('earn')->change();
        });
    }

    public function down(): void
    {
        Schema::table('point_histories', function (Blueprint $table) {
            $table->enum('type', ['earn', 'redeem'])->default('earn')->change();
        });
    }
};