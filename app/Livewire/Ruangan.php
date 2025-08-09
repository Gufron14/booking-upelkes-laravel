<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Ruang;
use App\Models\Cart;
use App\Models\Layanan;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

#[Title('Ruangan - Upelkes Jabar')]
class Ruangan extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedRuang;

    // Cart form properties
    public $showCartModal = false;
    public $selectedRuangForCart = null;
    public $namaKegiatan = '';
    public $tanggalCheckin = '';
    public $tanggalCheckout = '';
    public $jamMulai = '';
    public $jamSelesai = '';
    public $jumlahOrang = 1;

    // User/Instansi data untuk cart
    public $nama = '';
    public $email = '';
    public $no_hp = '';
    public $alamat = '';
    public $nama_instansi = '';
    public $alamat_instansi = '';
    public $jabatan_instansi = '';

    // Add Layanan With Ruang to Cart
    public function openCartModal($ruangId)
    {
        if (!Auth::check()) {
            session()->flash('error', 'Silakan login terlebih dahulu');
            return redirect()->route('login');
        }

        $this->selectedRuangForCart = $ruangId;
        $this->resetCartForm();
        $this->loadUserData();
        // Ambil value lama nama_kegiatan dari Cart
        $cart = Cart::where('user_id', Auth::id())->latest()->first();
        if ($cart && $cart->nama_kegiatan) {
            $this->namaKegiatan = $cart->nama_kegiatan;
        }
        $this->showCartModal = true;
    }

    public function resetCartForm()
    {
        $this->namaKegiatan = '';
        $this->tanggalCheckin = '';
        $this->tanggalCheckout = '';
        $this->jamMulai = '';
        $this->jamSelesai = '';
        $this->jumlahOrang = 1;
    }

    public function loadUserData()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $this->nama = $user->nama ?? '';
            $this->email = $user->email ?? '';
            $this->no_hp = $user->no_hp ?? '';
            $this->alamat = $user->alamat ?? '';
            $this->nama_instansi = $user->nama_instansi ?? '';
            $this->alamat_instansi = $user->alamat_instansi ?? '';
            $this->jabatan_instansi = $user->jabatan_instansi ?? '';
        }
    }

    public function addToCart()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Silakan login terlebih dahulu');
            return;
        }

        // Update user instansi fields
        $user = Auth::user();
        $user->nama_instansi = $this->nama_instansi;
        $user->alamat_instansi = $this->alamat_instansi;
        $user->jabatan_instansi = $this->jabatan_instansi;
        $user->save();

        $ruang = Ruang::with('layanan')->find($this->selectedRuangForCart);
        if (!$ruang) {
            session()->flash('error', 'Ruangan tidak ditemukan');
            return;
        }

        $layanan = $ruang->layanan;

        // Validasi berdasarkan satuan layanan
        $rules = [
            'namaKegiatan' => 'required|string|max:255',
            'jumlahOrang' => 'required|integer|min:1|max:' . ($layanan->kapasitas ?? 100),
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string|max:500',
            'nama_instansi' => 'required|string|max:255',
            'alamat_instansi' => 'required|string|max:500',
            'jabatan_instansi' => 'required|string|max:255',
        ];

        // Validasi tanggal berdasarkan jenis layanan
        if ($layanan->requiresDateRange()) {
            $rules['tanggalCheckin'] = 'required|date|after_or_equal:today';
            $rules['tanggalCheckout'] = 'required|date|after:tanggalCheckin';
        } elseif ($layanan->requiresSingleDate()) {
            $rules['tanggalCheckin'] = 'required|date|after_or_equal:today';
        }

        // Validasi waktu untuk layanan per jam
        if ($layanan->requiresTimeSelection()) {
            $rules['jamMulai'] = 'required';
            $rules['jamSelesai'] = 'required|after:jamMulai';
        }

        $this->validate($rules, [
            'namaKegiatan.required' => 'Nama kegiatan harus diisi',
            'tanggalCheckin.required' => 'Tanggal check-in harus diisi',
            'tanggalCheckin.after_or_equal' => 'Tanggal check-in tidak boleh kurang dari hari ini',
            'tanggalCheckout.required' => 'Tanggal check-out harus diisi',
            'tanggalCheckout.after' => 'Tanggal check-out harus setelah tanggal check-in',
            'jamMulai.required' => 'Jam mulai harus diisi',
            'jamSelesai.required' => 'Jam selesai harus diisi',
            'jamSelesai.after' => 'Jam selesai harus setelah jam mulai',
            'jumlahOrang.required' => 'Jumlah orang harus diisi',
            'jumlahOrang.min' => 'Jumlah orang minimal 1',
            'jumlahOrang.max' => 'Jumlah orang melebihi kapasitas ruangan',
            'nama.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'no_hp.required' => 'Nomor HP harus diisi',
            'alamat.required' => 'Alamat harus diisi',
            'nama_instansi.required' => 'Nama instansi harus diisi',
            'alamat_instansi.required' => 'Alamat instansi harus diisi',
            'jabatan_instansi.required' => 'Jabatan di instansi harus diisi'
        ]);

        // Hitung total biaya
        $totalBiaya = $this->calculateTotalBiaya($layanan);

        // Simpan ke cart
        Cart::create([
            'user_id' => Auth::id(),
            'layanan_id' => $layanan->id,
            'ruang_id' => $ruang->id,
            'nama_kegiatan' => $this->namaKegiatan,
            'tanggal_checkin' => $this->tanggalCheckin,
            'tanggal_checkout' => $this->tanggalCheckout ?: $this->tanggalCheckin,
            'jam_mulai' => $this->jamMulai,
            'jam_selesai' => $this->jamSelesai,
            'jumlah_orang' => $this->jumlahOrang,
            'total_biaya' => $totalBiaya,
            'status' => 'pending'
        ]);

        $this->showCartModal = false;
        $this->resetCartForm();
        session()->flash('success', 'Layanan berhasil ditambahkan ke keranjang');
    }

    private function calculateTotalBiaya($layanan)
    {
        $tarif = $layanan->tarif;

        switch ($layanan->satuan) {
            case Layanan::UNIT_PER_JAM:
                if ($this->jamMulai && $this->jamSelesai) {
                    $mulai = \Carbon\Carbon::parse($this->jamMulai);
                    $selesai = \Carbon\Carbon::parse($this->jamSelesai);
                    $totalJam = $selesai->diffInHours($mulai);
                    return $totalJam * $tarif;
                }
                return $tarif;

            case Layanan::UNIT_PER_HARI:
            case Layanan::UNIT_PER_KAMAR_HARI:
                $checkin = \Carbon\Carbon::parse($this->tanggalCheckin);
                $checkout = \Carbon\Carbon::parse($this->tanggalCheckout);
                $totalHari = max(1, $checkin->diffInDays($checkout));
                return $totalHari * $tarif;

            case Layanan::UNIT_PER_ORANG_HARI:
            case Layanan::UNIT_PER_KEGIATAN_HARI:
                $checkin = \Carbon\Carbon::parse($this->tanggalCheckin);
                $checkout = \Carbon\Carbon::parse($this->tanggalCheckout);
                $totalHari = max(1, $checkin->diffInDays($checkout));
                return $totalHari * $tarif * $this->jumlahOrang;

            case Layanan::UNIT_PER_ORANG_KUNJUNGAN:
                return $tarif * $this->jumlahOrang;

            case Layanan::UNIT_PER_BULAN:
                $checkin = \Carbon\Carbon::parse($this->tanggalCheckin);
                $checkout = \Carbon\Carbon::parse($this->tanggalCheckout);
                $totalBulan = max(1, $checkin->diffInMonths($checkout));
                return $totalBulan * $tarif;

            default:
                return $tarif;
        }
    }

    public function render()
    {
        $query = Ruang::with(['gambarRuangs', 'layanan.fasilitas']);

        if ($this->search) {
            $query->whereHas('layanan', function ($query) {
                $query->where('nama_layanan', 'like', '%' . $this->search . '%');
            });
        }

        $ruangList = $query->paginate(10);

        return view('livewire.ruangan', compact('ruangList'));
    }

    public function selectRuang($ruangId)
    {
        $this->selectedRuang = $ruangId;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $cart = Cart::where('user_id', $userId)->latest()->first();
            if ($cart && $cart->nama_kegiatan) {
                $this->namaKegiatan = $cart->nama_kegiatan;
            }
        }
    }
}