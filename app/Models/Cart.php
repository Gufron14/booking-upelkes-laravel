<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'booking_id',
        'layanan_id',
        'kamar_id',
        'ruang_id',
        'nama_kegiatan',
        'tanggal_checkin',
        'tanggal_checkout',
        'jam_mulai',
        'jam_selesai',
        'jumlah_orang',
        'total_biaya',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class);
    }

    public function ruang()
    {
        return $this->belongsTo(Ruang::class);
    }
}