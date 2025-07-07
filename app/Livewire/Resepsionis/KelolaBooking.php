<?php

namespace App\Livewire\Resepsionis;

use App\Models\Booking;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Title('Daftar Booking')]
#[Layout('components.layouts.admin-layout')]

class KelolaBooking extends Component
{
    use WithPagination;

    // Filter properties
    public $search = '';
    public $statusFilter = '';
    public $dateFrom = '';
    public $dateTo = '';

    // Selected booking for detail modal
    public $selectedBooking = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
    {
        $this->resetPage();
    }

    public function confirmBooking($bookingId)
    {
        try {
            $booking = Booking::findOrFail($bookingId);
            
            if ($booking->status !== 'pending') {
                session()->flash('error', 'Booking sudah dikonfirmasi atau dibatalkan.');
                return;
            }

            $booking->update(['status' => 'confirmed']);
            session()->flash('success', 'Booking berhasil dikonfirmasi.');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function cancelBooking($bookingId)
    {
        try {
            $booking = Booking::findOrFail($bookingId);
            
            if ($booking->status !== 'pending') {
                session()->flash('error', 'Booking sudah dikonfirmasi atau tidak dapat dibatalkan.');
                return;
            }

            $booking->update(['status' => 'cancelled']);
            session()->flash('success', 'Booking berhasil dibatalkan.');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function viewBookingDetail($bookingId)
    {
        $this->selectedBooking = Booking::with(['user', 'layanan', 'kamar', 'ruang'])->findOrFail($bookingId);
        $this->dispatch('show-detail-modal');
    }

    public function closeDetailModal()
    {
        $this->selectedBooking = null;
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    public function render()
    {
        $bookings = Booking::with(['user', 'layanan', 'kamar', 'ruang'])
            // ->whereHas('user', function ($query) {
            //     $query->whereHas('roles', function ($q) {
            //         $q->where('name', 'customer');
            //     });
            // })
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('layanan', function ($q) {
                    $q->where('nama_layanan', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->dateFrom, function ($query) {
                $query->where('tanggal_checkin', '>=', Carbon::parse($this->dateFrom));
            })
            ->when($this->dateTo, function ($query) {
                $query->where('tanggal_checkin', '<=', Carbon::parse($this->dateTo));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.resepsionis.kelola-booking', [
            'bookings' => $bookings,
        ]);
    }
}
