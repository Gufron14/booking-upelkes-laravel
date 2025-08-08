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
        Schema::create('gambar_ruangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ruang_id')->nullable()->constrained('ruangs')->onDelete('cascade');
            $table->foreignId('kamar_id')->nullable()->constrained('kamars')->onDelete('cascade');
            $table->string('path'); // Lokasi file gambar
            $table->string('keterangan')->nullable(); // Caption opsional
            $table->timestamps();
            
            // Pastikan salah satu dari ruang_id atau kamar_id harus ada
            // $table->check('ruang_id IS NOT NULL OR kamar_id IS NOT NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gambar_ruangs');
    }
};
