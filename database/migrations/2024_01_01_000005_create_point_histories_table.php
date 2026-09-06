<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Use case: "Melihat riwayat poin" (Resident).
     * Relasi 1-to-Many: User hasMany PointHistory.
     */
    public function up(): void
    {
        Schema::create('point_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pickup_request_id')->nullable()->constrained('pickup_requests')->onDelete('set null');
            $table->integer('points')->comment('Positif = earn, Negatif = redeem');
            $table->enum('type', ['earn', 'redeem']);
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_histories');
    }
};
