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
        Schema::table('bookings', function (Blueprint $table) {
            $table->time('jam_mulai')->nullable()->after('tanggal_checkout');
            $table->time('jam_selesai')->nullable()->after('jam_mulai');
            $table->integer('jumlah_orang')->default(1)->after('jam_selesai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['jam_mulai', 'jam_selesai', 'jumlah_orang']);
        });
    }
};
