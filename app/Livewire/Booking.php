<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Layanan;
use App\Models\Kamar;
use App\Models\Ruang;
use App\Models\Booking as ModelsBooking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;

#[Title('Booking Ruangan | Upelkes Jabar')]

class Booking extends Component
{
    public $step = 1;
    public $selectedLayanan = null;
    public $selectedKamar = null;
    public $selectedRuang = null;
    public $tanggal_checkin = '';
    public $tanggal_checkout = '';
    public $layananData = null;
    public $availableKamar = [];
    public $availableRuang = [];
    public $totalHari = 0;
    public $totalBiaya = 0;
    
    // User data
    public $nama = '';
    public $email = '';
    public $no_hp = '';
    public $alamat = '';

    protected $rules = [
        'selectedLayanan' => 'required',
        'tanggal_checkin' => 'required|date|after_or_equal:today',
        'tanggal_checkout' => 'required|date|after:tanggal_checkin',
        'nama' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'no_hp' => 'required|string|max:20',
        'alamat' => 'required|string|max:500',
    ];

    protected $messages = [
        'selectedLayanan.required' => 'Pilih layanan terlebih dahulu',
        'tanggal_checkin.required' => 'Tanggal check-in harus diisi',
        'tanggal_checkin.after_or_equal' => 'Tanggal check-in tidak boleh kurang dari hari ini',
        'tanggal_checkout.required' => 'Tanggal check-out harus diisi',
        'tanggal_checkout.after' => 'Tanggal check-out harus setelah tanggal check-in',
        'nama.required' => 'Nama harus diisi',
        'email.required' => 'Email harus diisi',
        'email.email' => 'Format email tidak valid',
        'no_hp.required' => 'Nomor HP harus diisi',
        'alamat.required' => 'Alamat harus diisi',
    ];

    public function mount($layanan_id = null)
    {
        if ($layanan_id) {
            $this->selectedLayanan = $layanan_id;
            $this->loadLayananData();
        }

        // Load user data if authenticated
        if (Auth::check()) {
            $user = Auth::user();
            $this->nama = $user->nama ?? '';
            $this->email = $user->email ?? '';
            $this->no_hp = $user->no_hp ?? '';
            $this->alamat = $user->alamat ?? '';
        }
    }

    public function selectLayanan($layananId)
    {
        $this->selectedLayanan = $layananId;
        $this->loadLayananData();
        $this->resetSelection();
    }

    public function loadLayananData()
    {
        if ($this->selectedLayanan) {
            $this->layananData = Layanan::with(['kamar', 'ruang', 'gambar'])->find($this->selectedLayanan);
        }
    }

    public function updatedTanggalCheckin()
    {
        $this->checkAvailability();
        $this->calculateTotal();
    }

    public function updatedTanggalCheckout()
    {
        $this->checkAvailability();
        $this->calculateTotal();
    }

    public function checkAvailability()
    {
        if (!$this->selectedLayanan || !$this->tanggal_checkin || !$this->tanggal_checkout) {
            return;
        }

        // Check available kamar
        $this->availableKamar = Kamar::where('layanan_id', $this->selectedLayanan)
            ->where('status', 'tersedia')
            ->whereDoesntHave('bookings', function($query) {
                $query->where(function($q) {
                    $q->whereBetween('tanggal_checkin', [$this->tanggal_checkin, $this->tanggal_checkout])
                      ->orWhereBetween('tanggal_checkout', [$this->tanggal_checkin, $this->tanggal_checkout])
                      ->orWhere(function($q2) {
                          $q2->where('tanggal_checkin', '<=', $this->tanggal_checkin)
                             ->where('tanggal_checkout', '>=', $this->tanggal_checkout);
                      });
                })->whereIn('status', ['confirmed', 'pending']);
            })
            ->get();

        // Check available ruang
        $this->availableRuang = Ruang::where('layanan_id', $this->selectedLayanan)
            ->whereDoesntHave('bookings', function($query) {
                $query->where(function($q) {
                    $q->whereBetween('tanggal_checkin', [$this->tanggal_checkin, $this->tanggal_checkout])
                      ->orWhereBetween('tanggal_checkout', [$this->tanggal_checkin, $this->tanggal_checkout])
                      ->orWhere(function($q2) {
                          $q2->where('tanggal_checkin', '<=', $this->tanggal_checkin)
                             ->where('tanggal_checkout', '>=', $this->tanggal_checkout);
                      });
                })->whereIn('status', ['confirmed', 'pending']);
            })
            ->get();
    }

    public function calculateTotal()
    {
        if (!$this->tanggal_checkin || !$this->tanggal_checkout || !$this->layananData) {
            return;
        }

        $checkin = Carbon::parse($this->tanggal_checkin);
        $checkout = Carbon::parse($this->tanggal_checkout);
        
        $this->totalHari = $checkin->diffInDays($checkout);
        
        // Calculate based on satuan
        switch ($this->layananData->satuan) {
            case 'per hari':
                $this->totalBiaya = $this->totalHari * $this->layananData->tarif;
                break;
            case 'per jam':
                $totalJam = $checkin->diffInHours($checkout);
                $this->totalBiaya = $totalJam * $this->layananData->tarif;
                break;
            case 'per bulan':
                $totalBulan = $checkin->diffInMonths($checkout);
                $this->totalBiaya = $totalBulan * $this->layananData->tarif;
                break;
            default:
                $this->totalBiaya = $this->layananData->tarif;
        }
    }

    public function selectKamar($kamarId)
    {
        $this->selectedKamar = $kamarId;
        $this->selectedRuang = null;
    }

    public function selectRuang($ruangId)
    {
        $this->selectedRuang = $ruangId;
        $this->selectedKamar = null;
    }

    public function nextStep()
    {
        if ($this->step == 1) {
            $this->validate([
                'selectedLayanan' => 'required',
                'tanggal_checkin' => 'required|date|after_or_equal:today',
                'tanggal_checkout' => 'required|date|after:tanggal_checkin',
            ]);
            
            if ($this->layananData->kamar->count() > 0 && !$this->selectedKamar) {
                session()->flash('error', 'Pilih kamar terlebih dahulu');
                return;
            }
            
            if ($this->layananData->ruang->count() > 0 && !$this->selectedRuang && !$this->selectedKamar) {
                session()->flash('error', 'Pilih ruang terlebih dahulu');
                return;
            }
        }

        $this->step++;
    }

    public function prevStep()
    {
        $this->step--;
    }

    public function resetSelection()
    {
        $this->selectedKamar = null;
        $this->selectedRuang = null;
        $this->availableKamar = [];
        $this->availableRuang = [];
        $this->step = 1;
    }

    public function submitBooking()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Create or update user
            $user = null;
            if (Auth::check()) {
                $user = Auth::user();
                $user->update([
                    'nama' => $this->nama,
                    'email' => $this->email,
                    'no_hp' => $this->no_hp,
                    'alamat' => $this->alamat,
                ]);
            } else {
                // Check if user exists
                $user = User::where('email', $this->email)->first();
                if (!$user) {
                    $user = User::create([
                        'nama' => $this->nama,
                        'email' => $this->email,
                        'no_hp' => $this->no_hp,
                        'alamat' => $this->alamat,
                        'password' => bcrypt('password123'), // Default password
                    ]);
                    $user->assignRole('customer');
                }
            }

            // Create booking
            $booking = ModelsBooking::create([
                'user_id' => $user->id,
                'layanan_id' => $this->selectedLayanan,
                'kamar_id' => $this->selectedKamar,
                'ruang_id' => $this->selectedRuang,
                'tanggal_checkin' => $this->tanggal_checkin,
                'tanggal_checkout' => $this->tanggal_checkout,
                'status' => 'pending',
            ]);

            DB::commit();

            session()->flash('success', 'Booking berhasil dibuat!');
            
            // Redirect to payment page with Livewire component
            return $this->redirect('/payment/' . $booking->id, navigate: true);

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $layananList = Layanan::with(['gambar', 'kamar', 'ruang'])->get();
        
        return view('livewire.booking', [
            'layananList' => $layananList
        ]);
    }
}
