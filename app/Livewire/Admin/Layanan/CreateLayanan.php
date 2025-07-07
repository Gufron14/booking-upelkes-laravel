<?php

namespace App\Livewire\Admin\Layanan;

use App\Models\Layanan;
use App\Models\Kamar;
use App\Models\Ruang;
use App\Models\Fasilitas;
use App\Models\GambarLayanan;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;

#[Title('Tambah Layanan')]
#[Layout('components.layouts.admin-layout')]
class CreateLayanan extends Component
{
    use WithFileUploads;

    // Form properties
    public $nama_layanan = '';
    public $tarif = '';
    public $kapasitas = '';
    public $deskripsi = '';
    public $gambar;
    public $kategori = 'umum';
    public $jenis = 'ruangan';
    public $satuan = 'per_jam';
    public $selectedFasilitas = [];

    // Additional properties for Kamar/Ruang
    public $nomor_kamar = '';
    public $kode_ruang = '';

    // Loading state
    public $isLoading = false;

    // Available options
    public $fasilitasList = [];

    protected $rules = [
        'nama_layanan' => 'required|string|max:255',
        'tarif' => 'required|numeric|min:0',
        'kapasitas' => 'nullable|integer|min:1',
        'deskripsi' => 'nullable|string',
        'gambar' => 'nullable|image|max:2048', // 2MB max
        'kategori' => 'required|in:umum,pemerintah',
        'jenis' => 'required|in:kamar,ruangan',
        'satuan' => 'required|string',
        'selectedFasilitas' => 'array',
        'nomor_kamar' => 'required_if:jenis,kamar|string|max:255',
        'kode_ruang' => 'required_if:jenis,ruangan|string|max:255',
    ];

    protected $messages = [
        'nama_layanan.required' => 'Nama layanan wajib diisi.',
        'tarif.required' => 'Tarif wajib diisi.',
        'tarif.numeric' => 'Tarif harus berupa angka.',
        'gambar.image' => 'File harus berupa gambar.',
        'gambar.max' => 'Ukuran gambar maksimal 2MB.',
        'nomor_kamar.required_if' => 'Nomor kamar wajib diisi untuk jenis kamar.',
        'kode_ruang.required_if' => 'Kode ruang wajib diisi untuk jenis ruangan.',
    ];

    public function mount()
    {
        $this->fasilitasList = Fasilitas::all();
    }

    public function updatedGambar()
    {
        $this->validate([
            'gambar' => 'image|max:2048',
        ]);
    }

    public function updatedJenis()
    {
        if ($this->jenis === 'kamar') {
            $this->kode_ruang = '';
        } else {
            $this->nomor_kamar = '';
        }
    }

    public function save()
    {
        $this->isLoading = true;

        try {
            $this->validate();

            // Create Layanan
            $layanan = Layanan::create([
                'nama_layanan' => $this->nama_layanan,
                'kategori' => $this->kategori,
                'satuan' => $this->satuan,
                'kapasitas' => $this->kapasitas,
                'tarif' => $this->tarif,
                'deskripsi' => $this->deskripsi,
            ]);

            // Handle image upload
            if ($this->gambar) {
                $path = $this->gambar->store('layanan-images', 'public');
                GambarLayanan::create([
                    'layanan_id' => $layanan->id,
                    'path' => $path,
                    'keterangan' => 'Gambar ' . $this->nama_layanan,
                ]);
            }

            // Create Kamar or Ruang based on jenis
            if ($this->jenis === 'kamar') {
                Kamar::create([
                    'layanan_id' => $layanan->id,
                    'nomor_kamar' => $this->nomor_kamar,
                    'status' => 'tersedia',
                ]);
            } elseif ($this->jenis === 'ruangan') {
                Ruang::create([
                    'layanan_id' => $layanan->id,
                    'kode_ruang' => $this->kode_ruang,
                ]);
            }

            // Attach selected fasilitas using pivot table
            if (!empty($this->selectedFasilitas)) {
                $layanan->fasilitas()->attach($this->selectedFasilitas);
            }

            session()->flash('success', 'Layanan berhasil ditambahkan!');
            return redirect()->route('layanan');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        } finally {
            $this->isLoading = false;
        }
    }

    public function render()
    {
        return view('livewire.admin.layanan.create-layanan');
    }
}
