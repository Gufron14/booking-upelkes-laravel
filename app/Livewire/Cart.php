<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Cart as ModelCart;
use App\Models\Booking;
use App\Models\User;

#[Title('Keranjang')]
class Cart extends Component
{
    public $carts;

    public function mount()
    {
        $this->carts = ModelCart::with([
            'user', 
            'booking',
            'layanan.gambar',
            'kamar.gambarRuangs',
            'ruang.gambarRuangs'
        ])
            ->where('user_id', auth()->id())
            ->get();
    }

    public function removeItem($cartId)
    {
        $cart = ModelCart::find($cartId);
        if ($cart) {
            $cart->delete();
            // Reload data from database to ensure proper relationships
            $this->carts = ModelCart::with([
                'user', 
                'booking',
                'layanan.gambar',
                'kamar.gambarRuangs',
                'ruang.gambarRuangs'
            ])
                ->where('user_id', auth()->id())
                ->get();
            session()->flash('message', 'Item berhasil dihapus dari keranjang');
        }
    }

    public function checkout()
    {
        if (count($this->carts) == 0) {
            session()->flash('error', 'Keranjang kosong, tidak dapat melakukan checkout');
            return;
        }

        try {
            // Loop through each cart item and create booking
            foreach ($this->carts as $cart) {
                $booking = Booking::create([
                    'user_id' => auth()->id(),
                    'layanan_id' => $cart->layanan_id,
                    'kamar_id' => $cart->kamar_id,
                    'ruang_id' => $cart->ruang_id,
                    'nama_kegiatan' => $cart->nama_kegiatan,
                    'tanggal_checkin' => $cart->tanggal_checkin,
                    'tanggal_checkout' => $cart->tanggal_checkout,
                    'jam_mulai' => $cart->jam_mulai,
                    'jam_selesai' => $cart->jam_selesai,
                    'jumlah_orang' => $cart->jumlah_orang,
                    // 'total_biaya' => $cart->total_biaya,
                    'status' => 'waiting_payment',
                    'payment_deadline' => now()->addHours(24)
                ]);

                // Update cart with booking_id
                $cart->update(['booking_id' => $booking->id]);
            }

            // Clear cart after successful checkout
            ModelCart::where('user_id', auth()->id())->delete();
            
            session()->flash('message', 'Checkout berhasil! Silakan lakukan pembayaran dalam 24 jam.');
            
            // Redirect to booking page or payment page
            return redirect()->route('riwayat');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Checkout gagal: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.cart');
    }
}