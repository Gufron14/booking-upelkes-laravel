<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Kamar as ModelsKamar;
use App\Models\Layanan;
use Livewire\Attributes\Title;

#[Title('Layanan | Upelkes Jabar')]
class Kamar extends Component
{
    public $search = '';
    public $kategori = '';
    public $selectedLayanan = null;

    public function selectLayanan($layananId)
    {
        $this->selectedLayanan = $layananId;
    }

    public function render()
    {
        $query = Layanan::with(['gambar', 'kamar' => function($q) {
            $q->where('status', 'tersedia');
        }]);

        if ($this->search) {
            $query->where('nama_layanan', 'like', '%' . $this->search . '%');
        }

        if ($this->kategori) {
            $query->where('kategori', $this->kategori);
        }

        $layananList = $query->get();

        return view('livewire.kamar', [
            'layananList' => $layananList
        ]);
    }
}
