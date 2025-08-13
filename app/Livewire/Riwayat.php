<?php

namespace App\Livewire;

use App\Models\Booking;
use App\Models\Layanan;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

#[Title('Riwayat Booking | Upelkes Jabar')]

class Riwayat extends Component
{
    public $layanan;
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

            // Hanya bisa cancel jika status waiting_payment
            // atau status pending dan metode pembayaran cash
            $canCancel = false;
            if ($booking->status === 'waiting_payment') {
                $canCancel = true;
            } elseif ($booking->status === 'pending' && $booking->payment && $booking->payment->metode_pembayaran === 'cash') {
                $canCancel = true;
            }

            if (!$canCancel) {
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