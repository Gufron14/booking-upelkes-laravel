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
        'bukti_transfer' => 'required|image|max:2048',
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

            DB::commit();

            session()->flash('success', 'Bukti pembayaran berhasil diupload! Menunggu verifikasi admin.');
            return redirect()->route('riwayat');
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.payment');
    }
}
