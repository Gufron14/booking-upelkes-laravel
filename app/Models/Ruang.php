<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruang extends Model
{
    use HasFactory;

    
    protected $fillable = ['layanan_id', 'kode_ruang'];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'ruang_id');
    }
}
