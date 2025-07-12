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

        // Layanan paling populer
        $popularLayanan = Layanan::withCount('bookings')->orderBy('bookings_count', 'desc')->take(5)->get();

        return view('livewire.admin.dashboard', compact('totalBookings', 'totalLayanan', 'totalKamar', 'totalFasilitas', 'totalRuang', 'totalCustomers', 'pendingBookings', 'confirmedBookings', 'cancelledBookings', 'kamarTersedia', 'kamarTidakTersedia', 'bookingHariIni', 'bookingBulanIni', 'totalPendapatan', 'recentBookings', 'popularLayanan', 'totalPerBooking'));
    }
}
