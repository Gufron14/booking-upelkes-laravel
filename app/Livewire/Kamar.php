<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Kamar as ModelsKamar;
use Livewire\Attributes\Title;

#[Title('Kamar - Upelkes Jabar')]
class Kamar extends Component
{
    public $search = '';
    public $kategori = '';
    public $selectedkamar = null;

    public function selectkamar($kamarId)
    {
        $this->selectedkamar = $kamarId;
    }

    public function render()
    {
        $query = ModelsKamar::with('layanan')->where('status', 'tersedia');

        if ($this->search) {
            $query->where('nama_kamar', 'like', '%' . $this->search . '%');
        }

        if ($this->kategori) {
            $query->where('kategori', $this->kategori);
        }

        $kamarList = $query->get();

        return view('livewire.kamar', [
            'kamarList' => $kamarList
        ]);
    }
}
