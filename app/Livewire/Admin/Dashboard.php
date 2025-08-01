<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use App\Models\Layanan;
use App\Models\Kamar;
use App\Models\Fasilitas;
use App\Models\Ruang;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Title('Dashboard Upelkes Jabar')]
#[Layout('components.layouts.admin-layout')]
class Dashboard extends Component
{
    public function render()
    {
        // Statistik umum
        $totalBookings = Booking::count();
        $totalLayanan = Layanan::count();
        $totalKamar = Kamar::count();
        $totalFasilitas = Fasilitas::count();
        $totalRuang = Ruang::count();
        $totalCustomers = User::whereHas('roles', function ($query) {
            $query->where('name', 'customer');
        })->count();

        // Statistik booking berdasarkan status
        $pendingBookings = Booking::pending()->count();
        $confirmedBookings = Booking::confirmed()->count();
        $cancelledBookings = Booking::cancelled()->count();

        // Statistik kamar
        $kamarTersedia = Kamar::tersedia()->count();
        $kamarTidakTersedia = Kamar::tidakTersedia()->count();

        // Booking hari ini
        $bookingHariIni = Booking::whereDate('created_at', Carbon::today())->count();

        // Booking bulan ini
        $bookingBulanIni = Booking::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // Total pendapatan (dari booking yang confirmed)
        $totalPendapatan = Booking::with('layanan')
        ->where('status', 'booked')
        ->whereMonth('tanggal_checkin', Carbon::now()->month)
        ->get()
        ->sum(function ($booking) {
            $durasi = $booking->tanggal_checkin->diffInDays($booking->tanggal_checkout);
            return $durasi * $booking->layanan->tarif;
        });

        $pendapatanPerBooking = Booking::with('layanan')
        ->where('status', 'booked')
        ->get()
        ->map(function ($booking) {
            $durasi = Carbon::parse($booking->checkin)->diffInDays(Carbon::parse($booking->checkout));
            $total = $durasi * $booking->layanan->tarif_per_hari;
    
            return [
                'booking_id' => $booking->id,
                'total_pendapatan' => $total,
            ];
        });

        $totalPerBooking = $pendapatanPerBooking->sum('total_pendapatan');
    

        // Booking terbaru (5 terakhir)
        $recentBookings = Booking::with(['user', 'layanan'])
            ->latest()
            ->take(5)
            ->get();

        // Data kalender - semua booking untuk kalender
        $calendarBookings = Booking::with(['user', 'layanan', 'kamar', 'ruang'])
            ->whereIn('status', ['pending', 'booked', 'confirmed'])
            ->get()
            ->map(function ($booking) {
                $roomInfo = '';
                $duration = '';
                
                if ($booking->kamar) {
                    $roomInfo = 'Kamar ' . $booking->kamar->nomor_kamar;
                } elseif ($booking->ruang) {
                    $roomInfo = 'Ruang ' . $booking->ruang->kode_ruang;
                } else {
                    $roomInfo = $booking->layanan->nama_layanan ?? 'N/A';
                }

                // Calculate duration based on service type
                if ($booking->layanan) {
                    switch ($booking->layanan->satuan) {
                        case \App\Models\Layanan::UNIT_PER_JAM:
                            if ($booking->jam_mulai && $booking->jam_selesai) {
                                $start = Carbon::parse($booking->jam_mulai);
                                $end = Carbon::parse($booking->jam_selesai);
                                $hours = $end->diffInHours($start);
                                $duration = " ({$hours} jam)";
                            }
                            break;
                        case \App\Models\Layanan::UNIT_PER_HARI:
                        case \App\Models\Layanan::UNIT_PER_ORANG_HARI:
                        case \App\Models\Layanan::UNIT_PER_KAMAR_HARI:
                        case \App\Models\Layanan::UNIT_PER_KEGIATAN_HARI:
                            if ($booking->tanggal_checkin && $booking->tanggal_checkout) {
                                $days = $booking->tanggal_checkin->diffInDays($booking->tanggal_checkout);
                                $duration = " ({$days} hari)";
                            }
                            break;
                        case \App\Models\Layanan::UNIT_PER_BULAN:
                            if ($booking->tanggal_checkin && $booking->tanggal_checkout) {
                                $months = $booking->tanggal_checkin->diffInMonths($booking->tanggal_checkout);
                                $duration = " ({$months} bulan)";
                            }
                            break;
                        case \App\Models\Layanan::UNIT_PER_ORANG_KUNJUNGAN:
                            $duration = " ({$booking->jumlah_orang} orang)";
                            break;
                    }
                }

                return [
                    'id' => $booking->id,
                    'title' => $roomInfo . $duration,
                    'start' => $booking->tanggal_checkin->format('Y-m-d'),
                    'end' => $booking->tanggal_checkout ? $booking->tanggal_checkout->format('Y-m-d') : $booking->tanggal_checkin->format('Y-m-d'),
                    'color' => $booking->status == 'booked' || $booking->status == 'confirmed' ? '#28a745' : ($booking->status == 'pending' ? '#ffc107' : '#dc3545'),
                    'textColor' => '#ffffff',
                    'description' => $booking->layanan->nama_layanan ?? 'N/A',
                    'customer' => $booking->user->nama ?? 'N/A'
                ];
            });

        // Layanan paling populer
        $popularLayanan = Layanan::withCount('bookings')->orderBy('bookings_count', 'desc')->take(5)->get();

        return view('livewire.admin.dashboard', compact('totalBookings', 'totalLayanan', 'totalKamar', 'totalFasilitas', 'totalRuang', 'totalCustomers', 'pendingBookings', 'confirmedBookings', 'cancelledBookings', 'kamarTersedia', 'kamarTidakTersedia', 'bookingHariIni', 'bookingBulanIni', 'totalPendapatan', 'recentBookings', 'popularLayanan', 'totalPerBooking', 'calendarBookings'));
    }
}
