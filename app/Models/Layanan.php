<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_layanan',
        'kategori',
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
}
