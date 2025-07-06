<?php

namespace Database\Seeders;

use App\Models\Layanan;
use App\Models\Fasilitas;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FasilitasLayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $map = [
            'Kamar Standar' => ['Kasur', 'Meja', 'Lemari', 'AC'],
            'Kamar Superior' => ['AC', 'TV', 'WiFi', 'Meja', 'Kamar Mandi'],
            'Ruang Multimedia' => ['Meja', 'Kursi', 'AC', 'Proyektor', 'Sound System'],
        ];

        foreach ($map as $layananNama => $fasilitasList) {
            $layanan = Layanan::where('nama_layanan', $layananNama)->first();
            $fasilitasIds = Fasilitas::whereIn('nama', $fasilitasList)->pluck('id');
            $layanan->fasilitas()->attach($fasilitasIds);
        }
    }
}
