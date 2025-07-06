<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

#[Title('Riwayat Booking | Upelkes Jabar')]
class Riwayat extends Component
{
    public function cancelBooking($bookingId)
    {
        try {
            $booking = Booking::where('id', $bookingId)
                ->where('user_id', Auth::id())
                ->first();

            if (!$booking) {
                session()->flash('error', 'Booking tidak ditemukan.');
                return;
            }

            if (!$booking->canBeCancelled()) {
                session()->flash('error', 'Booking tidak dapat dibatalkan.');
                return;
            }

            $booking->update(['status' => 'cancelled']);
            session()->flash('success', 'Booking berhasil dibatalkan.');

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat membatalkan booking.');
        }
    }

    public function render()
    {
        $bookings = Booking::with(['layanan', 'kamar', 'ruang', 'payment'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.riwayat', compact('bookings'));
    }
}
