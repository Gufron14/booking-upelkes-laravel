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
                'nama_layanan' => 'Peminjaman Ruang Rapat',
                'kategori' => 'umum',
                'satuan' => 'per_jam',
                'kapasitas' => 20,
                'tarif' => 150000,
                'deskripsi' => 'Ruang rapat tersedia dengan meja dan proyektor',
            ],
            [
                'nama_layanan' => 'Kamar Harian',
                'kategori' => 'umum',
                'satuan' => 'per_hari',
                'kapasitas' => 2,
                'tarif' => 300000,
                'deskripsi' => 'Kamar harian dengan fasilitas dasar',
            ],
            [
                'nama_layanan' => 'Sewa Bulanan Kantor',
                'kategori' => 'umum',
                'satuan' => 'per_bulan',
                'kapasitas' => 10,
                'tarif' => 5000000,
                'deskripsi' => 'Kantor lengkap dengan meja kerja dan koneksi internet',
            ],
            [
                'nama_layanan' => 'Pelatihan Per Orang per Hari',
                'kategori' => 'pemerintah',
                'satuan' => 'per_orang_hari',
                'kapasitas' => 50,
                'tarif' => 100000,
                'deskripsi' => 'Biaya pelatihan per orang per hari',
            ],
            [
                'nama_layanan' => 'Sewa Kamar Harian',
                'kategori' => 'umum',
                'satuan' => 'per_kamar_hari',
                'kapasitas' => 1,
                'tarif' => 250000,
                'deskripsi' => 'Kamar pribadi per malam',
            ],
            [
                'nama_layanan' => 'Paket Kegiatan Harian',
                'kategori' => 'umum',
                'satuan' => 'per_kegiatan_hari',
                'kapasitas' => null,
                'tarif' => 750000,
                'deskripsi' => 'Paket layanan untuk satu kegiatan penuh sehari',
            ],
            [
                'nama_layanan' => 'Biaya Kunjungan',
                'kategori' => 'pemerintah',
                'satuan' => 'per_orang_kunjungan',
                'kapasitas' => null,
                'tarif' => 50000,
                'deskripsi' => 'Biaya kunjungan per orang',
            ],
        ]);
    }
}
