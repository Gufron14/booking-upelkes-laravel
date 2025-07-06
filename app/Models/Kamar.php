<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    use HasFactory;


    protected $fillable = ['layanan_id', 'nomor_kamar', 'status'];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'kamar_id');
    }

    // Scope untuk kamar yang tersedia
    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia');
    }

    // Scope untuk kamar yang tidak tersedia
    public function scopeTidakTersedia($query)
    {
        return $query->where('status', '!=', 'tersedia');
    }
}
