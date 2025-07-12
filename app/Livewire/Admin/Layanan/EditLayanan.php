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

#[Title('Edit Layanan')]
#[Layout('components.layouts.admin-layout')]

class EditLayanan extends Component
{
    use WithFileUploads;

    // Layanan ID
    public $layananId;
    public $layanan;

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

    // Current image
    public $currentImage = null;

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

    public function mount($id)
    {
        $this->layananId = $id;
        $this->layanan = Layanan::with(['kamar', 'ruang', 'fasilitas', 'gambar'])->findOrFail($id);
        
        // Load fasilitas list
        $this->fasilitasList = Fasilitas::all();
        
        // Populate form with existing data
        $this->nama_layanan = $this->layanan->nama_layanan;
        $this->tarif = $this->layanan->tarif;
        $this->kapasitas = $this->layanan->kapasitas;
        $this->deskripsi = $this->layanan->deskripsi;
        $this->kategori = $this->layanan->kategori;
        $this->satuan = $this->layanan->satuan;
        
        // Determine jenis and populate related fields
        if ($this->layanan->kamar->isNotEmpty()) {
            $this->jenis = 'kamar';
            $this->nomor_kamar = $this->layanan->kamar->first()->nomor_kamar;
        } elseif ($this->layanan->ruang->isNotEmpty()) {
            $this->jenis = 'ruangan';
            $this->kode_ruang = $this->layanan->ruang->first()->kode_ruang;
        }

        
        // Load selected fasilitas
        $this->selectedFasilitas = $this->layanan->fasilitas->pluck('id')->toArray();
        
        // Load current image
        if ($this->layanan->gambar->isNotEmpty()) {
            $this->currentImage = $this->layanan->gambar->first()->path;
        }
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

    public function update()
    {
        $this->isLoading = true;

        try {
            $this->validate();

            // Update Layanan
            $this->layanan->update([
                'nama_layanan' => $this->nama_layanan,
                'kategori' => $this->kategori,
                'satuan' => $this->satuan,
                'kapasitas' => $this->kapasitas,
                'tarif' => $this->tarif,
                'deskripsi' => $this->deskripsi,
            ]);

            // Handle image upload
            if ($this->gambar) {
                // Delete old image if exists
                if ($this->currentImage) {
                    Storage::disk('public')->delete($this->currentImage);
                    GambarLayanan::where('layanan_id', $this->layanan->id)->delete();
                }
                
                // Upload new image
                $path = $this->gambar->store('layanan-images', 'public');
                GambarLayanan::create([
                    'layanan_id' => $this->layanan->id,
                    'path' => $path,
                    'keterangan' => 'Gambar ' . $this->nama_layanan,
                ]);
            }

            // Update Kamar or Ruang based on jenis
            if ($this->jenis === 'kamar') {
                // Delete ruang if exists
                if ($this->layanan->ruang->isNotEmpty()) {
                    $this->layanan->ruang->first()->delete();
                }
                
                // Update or create kamar
                Kamar::updateOrCreate(
                    ['layanan_id' => $this->layanan->id],
                    [
                        'nomor_kamar' => $this->nomor_kamar,
                        'status' => 'tersedia',
                    ]
                );
            } elseif ($this->jenis === 'ruangan') {
                // Delete kamar if exists
                if ($this->layanan->kamar->isNotEmpty()) {
                    $this->layanan->kamar->first()->delete();
                }
                
                // Update or create ruang
                Ruang::updateOrCreate(
                    ['layanan_id' => $this->layanan->id],
                    ['kode_ruang' => $this->kode_ruang]
                );
            }


            // Sync selected fasilitas
            $this->layanan->fasilitas()->sync($this->selectedFasilitas);

            session()->flash('success', 'Layanan berhasil diperbarui!');
            return redirect()->route('daftar.layanan');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        } finally {
            $this->isLoading = false;
        }
    }

    public function render()
    {
        return view('livewire.admin.layanan.edit-layanan');
    }
}
