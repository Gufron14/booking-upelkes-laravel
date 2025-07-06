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
        Schema::create('fasilitas_layanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('layanan_id')->constrained('layanans')->onDelete('cascade');
            $table->foreignId('fasilitas_id')->nullable()->constrained('fasilitas')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fasilitas_kamars');
    }
};
