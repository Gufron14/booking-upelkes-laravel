<?php

namespace App\Livewire;

use App\Models\Booking;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use App\Models\Payment as PaymentModel;
use Illuminate\Support\Facades\Storage;

#[Title('Pembayaran')]
class Payment extends Component
{
    use WithFileUploads;

    public $booking;
    public $bukti_transfer;
    public $metode_pembayaran = 'transfer';
    public $keterangan = '';

    protected $rules = [
        'bukti_transfer' => 'required_if:metode_pembayaran,transfer|image|max:2048',
        'metode_pembayaran' => 'required|in:transfer,cash',
        'keterangan' => 'nullable|string|max:500',
    ];

    protected $messages = [
        'bukti_transfer.required' => 'Bukti transfer harus diupload',
        'bukti_transfer.image' => 'File harus berupa gambar',
        'bukti_transfer.max' => 'Ukuran file maksimal 2MB',
        'metode_pembayaran.required' => 'Pilih metode pembayaran',
    ];

    public function mount($booking)
    {
        $this->booking = Booking::with(['user', 'layanan', 'kamar', 'ruang', 'payment'])->findOrFail($booking);

        // Cek deadline pembayaran
        if ($this->booking->status !== 'waiting_payment' || now()->greaterThan($this->booking->payment_deadline)) {
            session()->flash('error', 'Waktu pembayaran telah habis atau pembayaran sudah diproses.');
            return redirect()->route('riwayat');
        }
    }

    public function submitPayment()
    {
        $this->validate();

        // Cek deadline sebelum upload
        if (now()->greaterThan($this->booking->payment_deadline)) {
            session()->flash('error', 'Waktu pembayaran telah habis.');
            return redirect()->route('');
        }

        try {
            DB::beginTransaction();

            $buktiPath = $this->bukti_transfer->store('bukti-transfer', 'public');

            $payment = PaymentModel::updateOrCreate(
                ['booking_id' => $this->booking->id],
                [
                    'bukti_transfer' => $buktiPath,
                    'tanggal_bayar' => now(),
                    'status' => 'terverifikasi',
                    'jumlah_bayar' => $this->booking->calculateTotal(),
                    'metode_pembayaran' => $this->metode_pembayaran,
                    'keterangan' => $this->keterangan,
                ],
            );

            // Update status booking jadi 'pending' (atau 'waiting_verification')
            $this->booking->update(['status' => 'pending']);

            $user = $this->booking->user;

            // Send email notification
            $this->sendBookingNotification($this->booking, $user);

            DB::commit();

            session()->flash('success', 'Bukti pembayaran berhasil diupload! Menunggu verifikasi admin.');
            return redirect()->route('riwayat');
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    protected function sendBookingNotification($booking, $user)
    {
        $adminEmail = env('ADMIN_EMAIL', 'gupron.nurjalil14@gmail.com');
        $bookingUrl = url('/booking/' . $booking->id);

        $details = [
            'subject' => 'Booking Baru - ' . $booking->nama_kegiatan,
            'user_name' => $user->nama,
            'user_email' => $user->email,
            'user_phone' => $user->no_hp,
            'user_instansi' => $user->nama_instansi,
            'booking_activity' => $booking->nama_kegiatan,
            'booking_date' => $booking->tanggal_checkin,
            'booking_url' => $bookingUrl,
        ];

        \Mail::to($adminEmail)->send(new \App\Mail\BookingNotification($details));
    }

    public function render()
    {
        return view('livewire.payment');
    }
}
