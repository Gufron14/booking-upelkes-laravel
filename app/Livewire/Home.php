<?php

namespace App\Livewire;

use App\Models\Kamar;
use App\Models\Ruang;
use App\Models\Layanan;
use Livewire\Component;
use App\Models\Fasilitas;
use Livewire\Attributes\Title;

#[Title('Upelkes Jabar')]

class Home extends Component
{
    public $featuredLayanan;
    public $totalKamar;
    public $totalRuang;
    public $totalFasilitas;
    public $kategoriStats;

    public function mount()
    {
        $this->featuredLayanan = Layanan::with(['gambar', 'fasilitas'])
            ->take(6)
            ->get();
        
        $this->totalKamar = Kamar::where('status', 'tersedia')->count();
        $this->totalRuang = Ruang::count();
        $this->totalFasilitas = Fasilitas::count();
        
        $this->kategoriStats = [
            'umum' => Layanan::where('kategori', 'umum')->count(),
            'pemerintah' => Layanan::where('kategori', 'pemerintah')->count()
        ];
    }

    public function render()
    {
        return view('livewire.home');
    }
}
