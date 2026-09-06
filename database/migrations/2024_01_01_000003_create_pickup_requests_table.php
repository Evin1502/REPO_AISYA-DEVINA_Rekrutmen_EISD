<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Use case: "Mengajukan pengambilan sampah" (Resident),
     * "Mengelola data pengajuan sampah" (Admin),
     * "Melihat antrean penjemputan" / "Memperbarui riwayat penjemputan" (Collector).
     *
     * Relasi 1-to-Many:
     *  - User (resident) hasMany PickupRequest  (foreign key: user_id)
     *  - User (collector) hasMany PickupRequest (foreign key: collector_id)
     */
    public function up(): void
    {
        Schema::create('pickup_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('collector_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('address');
            $table->enum('status', ['pending', 'approved', 'scheduled', 'collected', 'rejected'])
                  ->default('pending');
            $table->dateTime('scheduled_at')->nullable();
            $table->decimal('total_weight', 8, 2)->nullable()->comment('Berat riil total (kg), diisi Collector');
            $table->unsignedInteger('total_points')->nullable()->comment('Poin yang didapat setelah penjemputan selesai');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pickup_requests');
    }
};
