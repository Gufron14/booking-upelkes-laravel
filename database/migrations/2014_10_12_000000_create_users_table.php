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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('no_hp')->nullable()->unique();
            $table->text('alamat')->nullable();

            // Instansi
            $table->string('nama_instansi')->nullable(); // Optional field for institution name
            $table->string('alamat_instansi')->nullable(); // Optional field for institution address
            $table->string('jabatan_instansi')->nullable(); // Optional field for position in the institution
            $table->string('foto_id_card')->nullable(); // Optional field for ID card picture
            $table->string('nip')->unique();

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
