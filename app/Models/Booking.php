<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'total_biaya',
        'catatan',
    ];

    protected $casts = [
        'tanggal_checkin' => 'date',
        'tanggal_checkout' => 'date',
        'total_biaya' => 'decimal:2',
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

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    // Methods
    public function getDurationAttribute()
    {
        return $this->tanggal_checkin->diffInDays($this->tanggal_checkout);
    }

    public function getFormattedCheckinAttribute()
    {
        return $this->tanggal_checkin->format('d M Y');
    }

    public function getFormattedCheckoutAttribute()
    {
        return $this->tanggal_checkout->format('d M Y');
    }

    public function canBeCancelled()
    {
        return $this->status === 'pending' && $this->tanggal_checkin->isFuture();
    }

    public function calculateTotalCost()
    {
        if (!$this->layanan) return 0;

        $days = $this->duration;
        
        switch ($this->layanan->satuan) {
            case 'per hari':
                return $days * $this->layanan->tarif;
            case 'per jam':
                $hours = $this->tanggal_checkin->diffInHours($this->tanggal_checkout);
                return $hours * $this->layanan->tarif;
            case 'per bulan':
                $months = $this->tanggal_checkin->diffInMonths($this->tanggal_checkout);
                return $months * $this->layanan->tarif;
            case 'per orang' :
                return $days * $this->layanan->tarif;
            case 'per kamar/hari' :
                return $days * $this->layanan->tarif;
            case 'per hari/kegiatan' :
                return $days * $this->layanan->tarif;
            default:
                return $this->layanan->tarif;
        }
    }
}
