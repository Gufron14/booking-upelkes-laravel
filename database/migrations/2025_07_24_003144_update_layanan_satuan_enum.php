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
        Schema::table('layanans', function (Blueprint $table) {
            // Update the satuan column to use proper enum values
            $table->enum('satuan', [
                'per_jam',
                'per_hari', 
                'per_bulan',
                'per_orang_hari',
                'per_kamar_hari',
                'per_kegiatan_hari',
                'per_orang_kunjungan'
            ])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('layanans', function (Blueprint $table) {
            $table->string('satuan')->change();
        });
    }
};
