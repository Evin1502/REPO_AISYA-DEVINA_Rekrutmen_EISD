<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * PIVOT TABLE - Relasi Many-to-Many WAJIB.
     *
     * Satu pengajuan (pickup_request) bisa berisi banyak kategori sampah,
     * dan satu kategori sampah bisa dipakai di banyak pengajuan.
     * Sesuai use case "Memilih kategori sampah" <<Include>> "Mengelola kategori sampah".
     *
     * Kolom tambahan pada pivot:
     *  - estimated_weight : estimasi berat saat Resident mengajukan
     *  - actual_weight    : berat riil hasil input Collector di lapangan
     */
    public function up(): void
    {
        Schema::create('pickup_request_waste_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pickup_request_id')->constrained('pickup_requests')->onDelete('cascade');
            $table->foreignId('waste_category_id')->constrained('waste_categories')->onDelete('cascade');
            $table->decimal('estimated_weight', 8, 2)->nullable();
            $table->decimal('actual_weight', 8, 2)->nullable();
            $table->timestamps();

            $table->unique(['pickup_request_id', 'waste_category_id'], 'prwc_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pickup_request_waste_category');
    }
};
