<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    // Unit types constants
    const UNIT_PER_JAM = 'per_jam';
    const UNIT_PER_HARI = 'per_hari';
    const UNIT_PER_BULAN = 'per_bulan';
    const UNIT_PER_ORANG_HARI = 'per_orang_hari';
    const UNIT_PER_KAMAR_HARI = 'per_kamar_hari';
    const UNIT_PER_KEGIATAN_HARI = 'per_kegiatan_hari';
    const UNIT_PER_ORANG_KUNJUNGAN = 'per_orang_kunjungan';

    protected $fillable = [
        'nama_layanan',
        'kategori',
        'jenis',
        'satuan',
        'kapasitas',
        'tarif',
        'deskripsi',
    ];

    public function fasilitas()
    {
        return $this->belongsToMany(Fasilitas::class, 'fasilitas_layanans');
    }

    public function gambar()
    {
        return $this->hasMany(GambarLayanan::class, 'layanan_id');
    }

    public function kamar()
    {
        return $this->hasMany(Kamar::class, 'layanan_id');
    }

    public function ruang()
    {
        return $this->hasMany(Ruang::class, 'layanan_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'layanan_id');
    }

    // Helper methods for unit types
    public function getSatuanLabelAttribute()
    {
        $labels = [
            self::UNIT_PER_JAM => 'Per Jam',
            self::UNIT_PER_HARI => 'Per Hari',
            self::UNIT_PER_BULAN => 'Per Bulan',
            self::UNIT_PER_ORANG_HARI => 'Per Orang/Hari',
            self::UNIT_PER_KAMAR_HARI => 'Per Kamar/Hari',
            self::UNIT_PER_KEGIATAN_HARI => 'Per Kegiatan/Hari',
            self::UNIT_PER_ORANG_KUNJUNGAN => 'Per Orang/Kunjungan',
        ];

        return $labels[$this->satuan] ?? $this->satuan;
    }

    public function requiresTimeSelection()
    {
        return $this->satuan === self::UNIT_PER_JAM;
    }

    public function requiresPersonCount()
    {
        return in_array($this->satuan, [
            self::UNIT_PER_ORANG_HARI,
            self::UNIT_PER_KEGIATAN_HARI,
            self::UNIT_PER_ORANG_KUNJUNGAN
        ]);
    }

    public function requiresRoomSelection()
    {
        return $this->satuan === self::UNIT_PER_KAMAR_HARI;
    }

    public function requiresDateRange()
    {
        return in_array($this->satuan, [
            self::UNIT_PER_HARI,
            self::UNIT_PER_BULAN,
            self::UNIT_PER_ORANG_HARI,
            self::UNIT_PER_KAMAR_HARI,
            self::UNIT_PER_KEGIATAN_HARI
        ]);
    }

    public function requiresSingleDate()
    {
        return in_array($this->satuan, [
            self::UNIT_PER_JAM,
            self::UNIT_PER_ORANG_KUNJUNGAN
        ]);
    }
}