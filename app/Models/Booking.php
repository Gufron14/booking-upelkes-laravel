<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'layanan_id', 'kamar_id', 'ruang_id', 'tanggal_checkin', 'tanggal_checkout', 'jam_mulai', 'jam_selesai', 'jumlah_orang', 'status', 'total_biaya', 'catatan', 'payment_deadline'];

    protected $casts = [
        'tanggal_checkin' => 'date',
        'tanggal_checkout' => 'date',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
        'total_biaya' => 'decimal:2',
        'payment_deadline' => 'datetime',
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
        if (!$this->layanan) {
            return 0;
        }

        switch ($this->layanan->satuan) {
            case \App\Models\Layanan::UNIT_PER_JAM:
                if ($this->jam_mulai && $this->jam_selesai && $this->tanggal_checkin) {
                    try {
                        $start = Carbon::parse("{$this->tanggal_checkin} {$this->jam_mulai}");
                        $end = Carbon::parse("{$this->tanggal_checkin} {$this->jam_selesai}");
                        if ($end->lessThanOrEqualTo($start)) {
                            $end->addDay();
                        }
                        return $start->diffInHours($end);
                    } catch (\Exception $e) {
                        return 0;
                    }
                }
                return 0;

            case \App\Models\Layanan::UNIT_PER_HARI:
            case \App\Models\Layanan::UNIT_PER_ORANG_HARI:
            case \App\Models\Layanan::UNIT_PER_KAMAR_HARI:
            case \App\Models\Layanan::UNIT_PER_KEGIATAN_HARI:
                if ($this->tanggal_checkin && $this->tanggal_checkout) {
                    return max(1, $this->tanggal_checkin->diffInDays($this->tanggal_checkout));
                }
                return 0;

            default:
                return 0;
        }
    }

    public function getTotalBulanAttribute()
    {
        if ($this->layanan && $this->layanan->satuan === \App\Models\Layanan::UNIT_PER_BULAN) {
            if ($this->tanggal_checkin && $this->tanggal_checkout) {
                return max(1, $this->tanggal_checkin->diffInMonths($this->tanggal_checkout));
            }
        }
        return 0;
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
        return $this->status === 'waiting_payment' && $this->tanggal_checkin->isFuture();
    }

    public function calculateTotal()
    {
        if (!$this->layanan) {
            return 0;
        }

        $layanan = $this->layanan;

        try {
            $checkin = Carbon::parse($this->tanggal_checkin);
            $checkout = Carbon::parse($this->tanggal_checkout);
        } catch (\Exception $e) {
            return 0;
        }

        // Konversi tarif ke float, hilangkan pemisah ribuan/koma desimal jika perlu
        $tarif = floatval(preg_replace('/[^\d.]/', '', $layanan->tarif ?? 0));

        switch ($layanan->satuan) {
            case Layanan::UNIT_PER_JAM:
                if ($this->jam_mulai && $this->jam_selesai) {
                    try {
                        $jamMulai = Carbon::parse("{$this->tanggal_checkin} {$this->jam_mulai}");
                        $jamSelesai = Carbon::parse("{$this->tanggal_checkin} {$this->jam_selesai}");

                        if ($jamSelesai->lessThanOrEqualTo($jamMulai)) {
                            $jamSelesai->addDay();
                        }

                        $totalJam = $jamMulai->diffInHours($jamSelesai);
                        return $totalJam * $tarif;
                    } catch (\Exception $e) {
                        return $tarif;
                    }
                }
                break;

            case Layanan::UNIT_PER_HARI:
                $totalHari = $checkin->diffInDays($checkout);
                return max(1, $totalHari) * $tarif;

            case Layanan::UNIT_PER_BULAN:
                $totalBulan = $checkin->diffInMonths($checkout);
                return max(1, $totalBulan) * $tarif;

            case Layanan::UNIT_PER_ORANG_HARI:
                $totalHari = $checkin->diffInDays($checkout);
                return max(1, $totalHari) * $tarif * $this->jumlah_orang;

            case Layanan::UNIT_PER_KAMAR_HARI:
                $totalHari = $checkin->diffInDays($checkout);
                return max(1, $totalHari) * $tarif;

            case Layanan::UNIT_PER_KEGIATAN_HARI:
                $totalHari = $checkin->diffInDays($checkout);
                return max(1, $totalHari) * $tarif * $this->jumlah_orang;

            case Layanan::UNIT_PER_ORANG_KUNJUNGAN:
                return $tarif * $this->jumlah_orang;

            default:
                return $tarif;
        }

        return 0;
    }
}
