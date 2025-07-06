<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('layanan_id')->constrained('layanans')->onDelete('cascade');
            $table->foreignId('kamar_id')->nullable()->constrained('kamars')->onDelete('set null');
            $table->foreignId('ruang_id')->nullable()->constrained('ruangs')->onDelete('set null');
            $table->dateTime('tanggal_checkin');
            $table->dateTime('tanggal_checkout');
            $table->enum('status', ['pending', 'booked', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
