<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'layanan_id',
        'kamar_id',
        'ruang_id',
        'tanggal_checkin',
        'tanggal_checkout',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'kamar_id');
    }

    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'ruang_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'booking_id');
    }
}
