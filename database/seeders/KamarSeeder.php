<?php

namespace Database\Seeders;

use App\Models\Kamar;
use App\Models\Layanan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KamarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $layanan = Layanan::where('nama_layanan', 'Kamar Superior')->first();

        for ($i = 1; $i <= 10; $i++) {
            Kamar::create([
                'layanan_id' => $layanan->id,
                'nomor_kamar' => 'S-' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'status' => 'tersedia'
            ]);
        }
    }
}
