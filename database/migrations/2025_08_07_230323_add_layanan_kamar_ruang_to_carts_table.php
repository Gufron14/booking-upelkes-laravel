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
        Schema::table('carts', function (Blueprint $table) {
            $table->foreignId('layanan_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('kamar_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('ruang_id')->nullable()->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['layanan_id']);
            $table->dropForeign(['kamar_id']);
            $table->dropForeign(['ruang_id']);
            $table->dropColumn(['layanan_id', 'kamar_id', 'ruang_id']);
        });
    }
};
