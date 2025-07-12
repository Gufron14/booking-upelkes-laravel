<?php

namespace App\Livewire\Resepsionis;

use Carbon\Carbon;
use App\Models\Booking;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Cetak Laporan Transaksi')]
#[Layout('components.layouts.print-layout')]

class CetakLaporan extends Component
{
    public $tanggal_mulai;
    public $tanggal_selesai;
    public $search;
    public $bookings;
    public $totalTransaksi;
    public $totalPendapatan;

    public function mount()
    {
        $this->tanggal_mulai = request('tanggal_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $this->tanggal_selesai = request('tanggal_selesai', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $this->search = request('search', '');

        $this->loadData();
    }

    private function loadData()
    {
        $query = Booking::with(['user', 'layanan', 'kamar', 'ruang'])
            ->where('status', 'booked');

        // Filter by search
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->whereHas('user', function ($userQuery) {
                    $userQuery->where('nama', 'like', '%' . $this->search . '%')
                             ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('layanan', function ($layananQuery) {
                    $layananQuery->where('nama_layanan', 'like', '%' . $this->search . '%');
                })
                ->orWhere('id', 'like', '%' . $this->search . '%');
            });
        }

        // Filter by date range
        if (!empty($this->tanggal_mulai)) {
            $query->whereDate('tanggal_checkin', '>=', $this->tanggal_mulai);
        }

        if (!empty($this->tanggal_selesai)) {
            $query->whereDate('tanggal_checkin', '<=', $this->tanggal_selesai);
        }

        $this->bookings = $query->orderBy('tanggal_checkin', 'desc')->get();
        $this->totalTransaksi = $this->bookings->count();

        $totalPendapatan = Booking::with('layanan')
        ->where('status', 'booked')
        ->get()
        ->sum(function ($booking) {
            $durasi = $booking->tanggal_checkin->diffInDays($booking->tanggal_checkout);
            return $durasi * $booking->layanan->tarif;
        });
        $this->totalPendapatan = $totalPendapatan;
    }

    public function render()
    {
        return view('livewire.resepsionis.cetak-laporan');
    }
}
