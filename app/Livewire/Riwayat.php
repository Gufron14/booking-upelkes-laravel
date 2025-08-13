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
    public $showRescheduleModal = false;
    public $rescheduleBookingId;
    public $reschedule_checkin;
    public $reschedule_checkout;
    public $reschedule_jam_mulai;
    public $reschedule_jam_selesai;

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
            if ($booking->kamar) {
                $booking->kamar->update(['status' => 'tersedia']);
            }
            session()->flash('success', 'Booking berhasil dibatalkan.');

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat membatalkan booking.');
        }
    }

    public function openRescheduleModal($bookingId)
    {
        $booking = Booking::where('id', $bookingId)
            ->where('user_id', Auth::id())
            ->with('payment')
            ->first();

        if (!$booking) {
            session()->flash('error', 'Booking tidak ditemukan.');
            return;
        }

        // Hanya boleh reschedule jika status waiting_payment
        // atau status pending dengan metode pembayaran cash
        $canReschedule = false;
        if ($booking->status === 'waiting_payment') {
            $canReschedule = true;
        } elseif ($booking->status === 'pending' && $booking->payment && $booking->payment->metode_pembayaran === 'cash') {
            $canReschedule = true;
        }

        if (!$canReschedule) {
            session()->flash('error', 'Booking tidak dapat di-reschedule.');
            return;
        }

        $this->rescheduleBookingId = $booking->id;
        $this->reschedule_checkin = $booking->tanggal_checkin;
        $this->reschedule_checkout = $booking->tanggal_checkout;
        $this->reschedule_jam_mulai = $booking->jam_mulai;
        $this->reschedule_jam_selesai = $booking->jam_selesai;
        $this->showRescheduleModal = true;

        // Dispatch event untuk membuka modal
        $this->dispatch('openRescheduleModal', bookingId: $bookingId);

        // Alternative: gunakan JavaScript event juga
        $this->js('window.dispatchEvent(new CustomEvent("openRescheduleModal", { detail: { bookingId: ' . $bookingId . ' } }));');
    }

    public function rescheduleBooking()
    {
        $booking = Booking::where('id', $this->rescheduleBookingId)
            ->where('user_id', Auth::id())
            ->with(['payment', 'layanan'])
            ->first();

        if (!$booking) {
            session()->flash('error', 'Booking tidak ditemukan.');
            return;
        }

        // Cek kelayakan reschedule, selaras dengan UI
        $canReschedule = false;
        if ($booking->status === 'waiting_payment') {
            $canReschedule = true;
        } elseif ($booking->status === 'pending' && $booking->payment && $booking->payment->metode_pembayaran === 'cash') {
            $canReschedule = true;
        }
        if (!$canReschedule) {
            session()->flash('error', 'Booking tidak dapat di-reschedule.');
            return;
        }

        // Validasi umum
        if (!$this->reschedule_checkin) {
            session()->flash('error', 'Tanggal check-in wajib diisi.');
            return;
        }

        $satuan = $booking->layanan->satuan ?? null;

        // Validasi khusus berdasarkan jenis layanan
        if ($satuan === 'per_jam') {
            if (!$this->reschedule_jam_mulai || !$this->reschedule_jam_selesai) {
                session()->flash('error', 'Jam mulai dan jam selesai wajib diisi untuk layanan per jam.');
                return;
            }
            if (strtotime($this->reschedule_jam_selesai) <= strtotime($this->reschedule_jam_mulai)) {
                session()->flash('error', 'Jam selesai harus lebih besar dari jam mulai.');
                return;
            }
            // Checkout opsional pada layanan per jam
        } elseif ($satuan === 'per_orang_kunjungan') {
            // Hanya butuh check-in pada layanan kunjungan
            $this->reschedule_checkout = null;
            $this->reschedule_jam_mulai = null;
            $this->reschedule_jam_selesai = null;
        } else {
            // Layanan menginap/menyewa: butuh check-in dan check-out valid
            if (!$this->reschedule_checkout) {
                session()->flash('error', 'Tanggal check-out wajib diisi.');
                return;
            }
            if (strtotime($this->reschedule_checkout) < strtotime($this->reschedule_checkin)) {
                session()->flash('error', 'Tanggal check-out tidak boleh lebih awal dari check-in.');
                return;
            }
        }

        // Simpan perubahan
        $booking->tanggal_checkin = $this->reschedule_checkin;
        $booking->tanggal_checkout = $this->reschedule_checkout;
        $booking->jam_mulai = $this->reschedule_jam_mulai;
        $booking->jam_selesai = $this->reschedule_jam_selesai;
        $booking->save();

        // Tutup modal dan reset state
        $this->showRescheduleModal = false;
        $this->dispatch('closeRescheduleModal');
        $this->reset(['rescheduleBookingId', 'reschedule_checkin', 'reschedule_checkout', 'reschedule_jam_mulai', 'reschedule_jam_selesai']);

        session()->flash('success', 'Booking berhasil di-reschedule.');
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