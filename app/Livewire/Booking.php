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
    public $tanggal_kunjungan = '';
    public $layananId;
    public $layanan;
    public $layananData = null;
    public $availableKamar = [];
    public $availableRuang = [];
    public $totalHari = 0;
    public $totalBulan = 0;
    public $totalBiaya = 0;

    public $jam_mulai = '';
    public $jam_selesai = '';
    public $jumlah_orang = 1;
    public $totalJam = 0;

    
    // User data
    public $nama = '';
    public $email = '';
    public $no_hp = '';
    public $alamat = '';

    protected function rules()
    {
        if (!$this->layananData) {
            return ['selectedLayanan' => 'required'];
        }

        $rules = [
            'selectedLayanan' => 'required',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string|max:500',
        ];

        // Date validation based on unit type
        if ($this->layananData->requiresSingleDate()) {
            if ($this->layananData->satuan === Layanan::UNIT_PER_JAM) {
                $rules['tanggal_checkin'] = 'required|date|after_or_equal:today';
                $rules['jam_mulai'] = 'required|date_format:H:i';
                $rules['jam_selesai'] = 'required|date_format:H:i|after:jam_mulai';
            } elseif ($this->layananData->satuan === Layanan::UNIT_PER_ORANG_KUNJUNGAN) {
                $rules['tanggal_kunjungan'] = 'required|date|after_or_equal:today';
            }
        } elseif ($this->layananData->requiresDateRange()) {
            $rules['tanggal_checkin'] = 'required|date|after_or_equal:today';
            $rules['tanggal_checkout'] = 'required|date|after:tanggal_checkin';
        }

        // Person count validation
        if ($this->layananData->requiresPersonCount()) {
            $maxCapacity = $this->layananData->kapasitas ?? 100;
            $rules['jumlah_orang'] = "required|integer|min:1|max:{$maxCapacity}";
        }

        // Room selection validation
        if ($this->layananData->requiresRoomSelection() && $this->layananData->kamar->count() > 0) {
            $rules['selectedKamar'] = 'required';
        }

        return $rules;
    }
    

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

        // Initialize empty arrays for availability
        $this->availableKamar = [];
        $this->availableRuang = [];

                $this->layananId = $layanan_id;
        $this->layanan = Layanan::findOrFail($layanan_id);
        $this->loadAvailableRoomsAndSpaces();
    }

        public function loadAvailableRoomsAndSpaces()
    {
        if ($this->layanan->kamar()->exists()) {
            $this->availableKamar = $this->layanan->kamar()->where('status', 'tersedia')->get();
        }

        if ($this->layanan->ruang()->exists()) {
            $this->availableRuang = $this->layanan->ruang()->where('status', 'tersedia')->get();
        }
    }

    public function selectLayanan($layananId)
    {
        $this->selectedLayanan = $layananId;
        $this->loadLayananData();
        $this->checkAvailability();
        $this->dispatch('update-url', layananId: $layananId);

        // $this->resetSelection();
    }

    

    public function loadLayananData()
    {
        if ($this->selectedLayanan) {
            $this->layananData = Layanan::with(['kamar', 'ruang', 'gambar'])->find($this->selectedLayanan);
            $this->checkAvailability();
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
        if (!$this->selectedLayanan || !$this->layananData) {
            return;
        }

        // Determine date range based on satuan type
        $startDate = null;
        $endDate = null;

        if ($this->layananData->requiresDateRange()) {
            if (!$this->tanggal_checkin || !$this->tanggal_checkout) {
                return;
            }
            $startDate = $this->tanggal_checkin;
            $endDate = $this->tanggal_checkout;
        } elseif ($this->layananData->satuan === 'per_jam') {
            if (!$this->tanggal_checkin) {
                return;
            }
            $startDate = $this->tanggal_checkin;
            $endDate = $this->tanggal_checkin;
        } elseif ($this->layananData->satuan === 'per_orang_kunjungan') {
            if (!$this->tanggal_kunjungan) {
                return;
            }
            $startDate = $this->tanggal_kunjungan;
            $endDate = $this->tanggal_kunjungan;
        } else {
            return;
        }

        // Check available kamar
        $this->availableKamar = Kamar::where('layanan_id', $this->selectedLayanan)
            ->where('status', 'tersedia')
            ->whereDoesntHave('bookings', function($query) use ($startDate, $endDate) {
                $query->where(function($q) use ($startDate, $endDate) {
                    $q->whereBetween('tanggal_checkin', [$startDate, $endDate])
                      ->orWhereBetween('tanggal_checkout', [$startDate, $endDate])
                      ->orWhere(function($q2) use ($startDate, $endDate) {
                          $q2->where('tanggal_checkin', '<=', $startDate)
                             ->where('tanggal_checkout', '>=', $endDate);
                      });
                })->whereIn('status', ['booked', 'pending']);
            })
            ->get();

        // Check available ruang
        $this->availableRuang = Ruang::where('layanan_id', $this->selectedLayanan)
            ->whereDoesntHave('bookings', function($query) use ($startDate, $endDate) {
                $query->where(function($q) use ($startDate, $endDate) {
                    $q->whereBetween('tanggal_checkin', [$startDate, $endDate])
                      ->orWhereBetween('tanggal_checkout', [$startDate, $endDate])
                      ->orWhere(function($q2) use ($startDate, $endDate) {
                          $q2->where('tanggal_checkin', '<=', $startDate)
                             ->where('tanggal_checkout', '>=', $endDate);
                      });
                })->whereIn('status', ['booked', 'pending']);
            })
            ->get()
            ->toArray();
    }

    public function calculateTotal()
    {
        if (!$this->layananData) {
            return;
        }

        $this->totalBiaya = 0;
        $this->totalHari = 0;
        $this->totalBulan = 0;
        $this->totalJam = 0;

        switch ($this->layananData->satuan) {
            case Layanan::UNIT_PER_JAM:
                if ($this->tanggal_checkin && $this->jam_mulai && $this->jam_selesai) {
                    try {
                        // Extract time part if it's a datetime object or string
                        $jamMulaiStr = is_object($this->jam_mulai) ? $this->jam_mulai->format('H:i:s') : $this->jam_mulai;
                        $jamSelesaiStr = is_object($this->jam_selesai) ? $this->jam_selesai->format('H:i:s') : $this->jam_selesai;
                        
                        // Create Carbon instances for the same date with different times
                        $jamMulai = Carbon::parse($this->tanggal_checkin . ' ' . $jamMulaiStr);
                        $jamSelesai = Carbon::parse($this->tanggal_checkin . ' ' . $jamSelesaiStr);
                        
                        $this->totalJam = $jamMulai->diffInHours($jamSelesai);
                        $this->totalBiaya = $this->totalJam * $this->layananData->tarif;
                    } catch (\Exception $e) {
                        // Fallback: assume 1 hour if parsing fails
                        $this->totalJam = 1;
                        $this->totalBiaya = $this->layananData->tarif;
                    }
                }
                break;

            case Layanan::UNIT_PER_HARI:
                if ($this->tanggal_checkin && $this->tanggal_checkout) {
                    $checkin = Carbon::parse($this->tanggal_checkin);
                    $checkout = Carbon::parse($this->tanggal_checkout);
                    $this->totalHari = $checkin->diffInDays($checkout);
                    $this->totalBiaya = $this->totalHari * $this->layananData->tarif;
                }
                break;

            case Layanan::UNIT_PER_BULAN:
                if ($this->tanggal_checkin && $this->tanggal_checkout) {
                    $checkin = Carbon::parse($this->tanggal_checkin);
                    $checkout = Carbon::parse($this->tanggal_checkout);
                    $totalBulan = $checkin->diffInMonths($checkout);
                    $this->totalBiaya = $totalBulan * $this->layananData->tarif;
                }
                break;

            case Layanan::UNIT_PER_ORANG_HARI:
                if ($this->tanggal_checkin && $this->tanggal_checkout) {
                    $checkin = Carbon::parse($this->tanggal_checkin);
                    $checkout = Carbon::parse($this->tanggal_checkout);
                    $this->totalHari = $checkin->diffInDays($checkout);
                    $this->totalBiaya = $this->totalHari * $this->layananData->tarif * $this->jumlah_orang;
                }
                break;

            case Layanan::UNIT_PER_KAMAR_HARI:
                if ($this->tanggal_checkin && $this->tanggal_checkout) {
                    $checkin = Carbon::parse($this->tanggal_checkin);
                    $checkout = Carbon::parse($this->tanggal_checkout);
                    $this->totalHari = $checkin->diffInDays($checkout);
                    $this->totalBiaya = $this->totalHari * $this->layananData->tarif;
                }
                break;

            case Layanan::UNIT_PER_KEGIATAN_HARI:
                if ($this->tanggal_checkin && $this->tanggal_checkout) {
                    $checkin = Carbon::parse($this->tanggal_checkin);
                    $checkout = Carbon::parse($this->tanggal_checkout);
                    $this->totalHari = $checkin->diffInDays($checkout);
                    $this->totalBiaya = $this->totalHari * $this->layananData->tarif * $this->jumlah_orang;
                }
                break;

            case Layanan::UNIT_PER_ORANG_KUNJUNGAN:
                if ($this->tanggal_kunjungan) {
                    $this->totalBiaya = $this->layananData->tarif * $this->jumlah_orang;
                }
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
            // Basic validation
            $rules = ['selectedLayanan' => 'required'];
            
            // Date validation based on unit type
            if ($this->layananData->requiresDateRange()) {
                $rules['tanggal_checkin'] = 'required|date|after_or_equal:today';
                $rules['tanggal_checkout'] = 'required|date|after:tanggal_checkin';
            } elseif ($this->layananData->satuan === 'per_jam') {
                $rules['tanggal_checkin'] = 'required|date|after_or_equal:today';
                $rules['jam_mulai'] = 'required|date_format:H:i';
                $rules['jam_selesai'] = 'required|date_format:H:i|after:jam_mulai';
            } elseif ($this->layananData->satuan === 'per_orang_kunjungan') {
                $rules['tanggal_kunjungan'] = 'required|date|after_or_equal:today';
            }
            
            // Person count validation
            if ($this->layananData->requiresPersonCount()) {
                $maxCapacity = $this->layananData->kapasitas ?? 100;
                $rules['jumlah_orang'] = "required|integer|min:1|max:{$maxCapacity}";
            }
            
            $this->validate($rules);
            
            // Room selection validation
if ($this->layananData->requiresRoomSelection() && $this->layananData->kamar->count() > 0 && !$this->selectedKamar) {
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

            // Prepare booking data
            $bookingData = [
                'user_id' => $user->id,
                'layanan_id' => $this->selectedLayanan,
                'kamar_id' => $this->selectedKamar,
                'ruang_id' => $this->selectedRuang,
                'jumlah_orang' => $this->jumlah_orang,
                'status' => 'waiting_payment',
                'payment_deadline' => Carbon::now()->addMinutes(5), // Set payment deadline to 1 day from now
                // 'total_biaya' => $this->totalBiaya,
            ];

            // Set dates based on unit type
            if ($this->layananData->requiresSingleDate()) {
                if ($this->layananData->satuan === Layanan::UNIT_PER_JAM) {
                    $bookingData['tanggal_checkin'] = $this->tanggal_checkin;
                    $bookingData['tanggal_checkout'] = $this->tanggal_checkin;
                    $bookingData['jam_mulai'] = $this->jam_mulai;
                    $bookingData['jam_selesai'] = $this->jam_selesai;
                } elseif ($this->layananData->satuan === Layanan::UNIT_PER_ORANG_KUNJUNGAN) {
                    $bookingData['tanggal_checkin'] = $this->tanggal_kunjungan;
                    $bookingData['tanggal_checkout'] = $this->tanggal_kunjungan;
                }
            } else {
                $bookingData['tanggal_checkin'] = $this->tanggal_checkin;
                $bookingData['tanggal_checkout'] = $this->tanggal_checkout;
            }

            // Create booking
            $booking = ModelsBooking::create($bookingData);


            DB::commit();

            session()->flash('success', 'Booking berhasil dibuat!');
            
            // Redirect to payment page with Livewire component
            return $this->redirect('/payment/' . $booking->id, navigate: true);

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updatedJamMulai()
    {
        $this->calculateTotal();
    }

    public function updatedJamSelesai()
    {
        $this->calculateTotal();
    }

    public function updatedJumlahOrang()
    {
        $this->calculateTotal();
    }

    public function updatedTanggalKunjungan()
    {
        $this->checkAvailability();
        $this->calculateTotal();
    }

    public function render()
    {
        $layananList = Layanan::with(['gambar', 'kamar', 'ruang'])->get();
        
        return view('livewire.booking', [
            'layananList' => $layananList
        ]);
    }
}
