<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['booking_id', 'bukti_transfer', 'tanggal_bayar', 'status'];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
