<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Ruang;
use Livewire\WithPagination;

#[Title('Ruangan - Upelkes Jabar')]
class Ruangan extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedRuang;

    public function render()
    {
        $ruangList = Ruang::with(['layanan.gambar', 'layanan.fasilitas'])
            ->whereHas('layanan', function ($query) {
                $query->where('nama_layanan', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

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
}