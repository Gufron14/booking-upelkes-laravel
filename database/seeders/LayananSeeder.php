<?php

namespace Database\Seeders;

use App\Models\Layanan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Layanan::insert([
            [
                'nama_layanan' => 'Kamar Standar',
                'kategori' => 'pemerintah',
                'satuan' => 'per orang',
                'kapasitas' => null,
                'tarif' => 125000,
                'deskripsi' => 'Kamar sederhana dengan fasilitas dasar',
            ],
            [
                'nama_layanan' => 'Kamar Superior',
                'kategori' => 'umum',
                'satuan' => 'per hari',
                'kapasitas' => 6,
                'tarif' => 400000,
                'deskripsi' => 'Kamar luas dengan AC dan TV',
            ],
            [
                'nama_layanan' => 'Ruang Multimedia',
                'kategori' => 'umum',
                'satuan' => 'per jam',
                'kapasitas' => 150,
                'tarif' => 300000,
                'deskripsi' => 'Ruang multimedia lengkap dengan proyektor dan sound system',
            ]
        ]);
    }
}
