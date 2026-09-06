<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Use case: "Mengelola kategori sampah" (Admin)
     * dan "Memilih kategori sampah" (Resident, <<Include>>).
     */
    public function up(): void
    {
        Schema::create('waste_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('points_per_kg');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waste_categories');
    }
};
