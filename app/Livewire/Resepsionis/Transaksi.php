<?php

namespace App\Livewire\Resepsionis;

use App\Models\Booking;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Title('Transaksi')]
#[Layout('components.layouts.admin-layout')]

class Transaksi extends Component
{
    use WithPagination;

    public $search = '';
    public $tanggal_mulai = '';
    public $tanggal_selesai = '';
    public $perPage = 10;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        // Set default date range to current month
        $this->tanggal_mulai = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->tanggal_selesai = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTanggalMulai()
    {
        $this->resetPage();
    }

    public function updatingTanggalSelesai()
    {
        $this->resetPage();
    }

    public function resetFilter()
    {
        $this->search = '';
        $this->tanggal_mulai = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->tanggal_selesai = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->resetPage();
    }

    public function cetakLaporan()
    {
        $bookings = $this->getFilteredBookings()->get();
        
        return redirect()->route('cetak-laporan', [
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_selesai' => $this->tanggal_selesai,
            'search' => $this->search
        ]);
    }

    private function getFilteredBookings()
    {
        $query = Booking::with(['user', 'layanan', 'kamar', 'ruang'])
            ->where('status', 'booked'); // Status booked/confirmed

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

        return $query->orderBy('tanggal_checkin', 'desc');
    }

    public function render()
    {
        $bookings = $this->getFilteredBookings()->paginate($this->perPage);
        
        // Calculate summary
        $totalTransaksi = $this->getFilteredBookings()->count();
        // $totalPendapatan = $this->getFilteredBookings()->sum('total_biaya');

        $totalPendapatan = Booking::with('layanan')
        ->where('status', 'booked')
        ->get()
        ->sum(function ($booking) {
            $durasi = $booking->tanggal_checkin->diffInDays($booking->tanggal_checkout);
            return $durasi * $booking->layanan->tarif;
        });
        

        return view('livewire.resepsionis.transaksi', [
            'bookings' => $bookings,
            'totalTransaksi' => $totalTransaksi,
            'totalPendapatan' => $totalPendapatan
        ]);
    }
}
