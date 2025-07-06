<?php

namespace Database\Seeders;

use App\Models\Ruang;
use App\Models\Layanan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RuangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $layanan = Layanan::where('nama_layanan', 'Ruang Multimedia')->first();

        Ruang::create([
            'layanan_id' => $layanan->id,
            'kode_ruang' => 'RM-01'
        ]);
    }
}
