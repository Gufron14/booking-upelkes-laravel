<?php

namespace Database\Seeders;

use App\Models\Fasilitas;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FasilitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fasilitas = [
            'AC', 'TV', 'WiFi', 'Meja', 'Kursi', 'Lemari', 'Kamar Mandi', 'Kasur',
            'Sound System', 'Proyektor', 'Whiteboard'
        ];

        foreach ($fasilitas as $nama) {
            Fasilitas::create(['nama' => $nama]);
        }
    }
}
