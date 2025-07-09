<?php

namespace App\Livewire\Admin;

use App\Models\Fasilitas as ModelsFasilitas;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Kelola Fasilitas')]
#[Layout('components.layouts.admin-layout')]
class Fasilitas extends Component
{   
    public $nama, $fasilitas;

    protected $rules = [
        // 'layanan_id' => 'required',
        'nama' => 'required|unique:fasilitas,nama',
        // 'status' => 'required|in:tersedia,dipesan',
    ];

    public function mount()
    {
        $this->fasilitas = ModelsFasilitas::with('layanan')->get();
    }

    public $editingId = null;

    public function edit($id)
    {
        $fasilitas = ModelsFasilitas::find($id);
        $this->editingId = $id;
        // $this->layanan_id = $fasilitas->layanan_id;
        $this->nama = $fasilitas->nama;
    }

    public function update()
    {
        $this->rules['nama'] = 'required|unique:fasilitas,nama,' . $this->editingId;
        $this->validate();

        $fasilitas = ModelsFasilitas::find($this->editingId);
        $fasilitas->update([
            // 'layanan_id' => $this->layanan_id,
            'nama' => $this->nama,
        ]);

        session()->flash('success', 'Fasilitas berhasil diperbarui.');

        $this->reset();
        $this->fasilitas = ModelsFasilitas::with('layanan')->get();

        return $this->redirect(request()->header('Referer'), navigate: true);
    }

    public function cancelEdit()
    {
        $this->reset();
    }

    public function save()
    {
        $this->validate();
    
        $fasilitas = ModelsFasilitas::create([
            // 'layanan_id' => $this->layanan_id,
            'nama' => $this->nama,
        ]);
    
        session()->flash('success', 'fasilitas berhasil disimpan.');
    
        $this->reset();
        $this->fasilitas = ModelsFasilitas::with('layanan')->get(); // Refresh data
    
        return $this->redirect(request()->header('Referer'), navigate: true);
    }
    
    public function delete($id)
    {
        ModelsFasilitas::find($id)->delete();
        session()->flash('success', 'fasilitas berhasil dihapus.');
        
        $this->fasilitas = ModelsFasilitas::with('layanan')->get(); // Refresh data
        
        return $this->redirect(request()->header('Referer'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.fasilitas');
    }
}
