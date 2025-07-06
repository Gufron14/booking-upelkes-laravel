<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Booking;
use App\Models\Payment as PaymentModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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
        
        // Check if payment already exists
        if ($this->booking->payment && $this->booking->payment->status !== 'pending') {
            session()->flash('error', 'Pembayaran sudah diproses sebelumnya');
            return redirect()->route('booking.history');
        }
    }

    public function submitPayment()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Upload bukti transfer
            $buktiPath = $this->bukti_transfer->store('bukti-transfer', 'public');

            // Create or update payment
            $payment = PaymentModel::updateOrCreate(
                ['booking_id' => $this->booking->id],
                [
                    'bukti_transfer' => $buktiPath,
                    'tanggal_bayar' => now(),
                    'status' => 'terverifikasi',
                    'jumlah_bayar' => $this->booking->calculateTotalCost(),
                    'metode_pembayaran' => $this->metode_pembayaran,
                    'keterangan' => $this->keterangan,
                ]
            );

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
