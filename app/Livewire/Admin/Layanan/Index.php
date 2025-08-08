<?php

namespace App\Livewire\Admin\Layanan;

use App\Models\Kamar;
use App\Models\Ruang;
use App\Models\Layanan;
use Livewire\Component;
use App\Models\Fasilitas;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Kelola Layanan')]
#[Layout('components.layouts.admin-layout')]

class Index extends Component
{
    public $search = '';
    public $jenisFilter = '';
    public $totalKamar;
    public $totalRuang;
    public $totalFasilitas;
    public $kategoriStats;

    public function mount()
    {
        $this->totalKamar = Kamar::where('status', 'tersedia')->count();
        $this->totalRuang = Ruang::count();
        $this->totalFasilitas = Fasilitas::count();

        $this->kategoriStats = [
            'kamar' => Layanan::where('jenis', 'kamar')->count(),
            'ruangan' => Layanan::where('jenis', 'ruangan')->count()
        ];
    }

    public function getFeaturedLayananProperty()
    {
        return Layanan::with(['gambar', 'fasilitas'])
            ->when($this->search, function ($query) {
                $query->where('nama_layanan', 'like', '%' . $this->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
            })
            ->when($this->jenisFilter, function ($query) {
                $query->where('jenis', $this->jenisFilter);
            })
            ->get();
    }

    // Fungsi Hapus Layanan
    public function deleteLayanan($id)
    {
        $layanan = Layanan::find($id);
        $layanan->delete();

        return redirect()->route('daftar.layanan')->with('success', 'Layanan berhasil dihapus.');
    }

    public function render()
    {
        return view('livewire.admin.layanan.index');
    }
}