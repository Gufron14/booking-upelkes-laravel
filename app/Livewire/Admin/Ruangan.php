<?php

namespace App\Livewire\Admin;

use App\Models\Ruang;
use App\Models\Layanan;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Kelola Ruangan')]
#[Layout('components.layouts.admin-layout')]
class Ruangan extends Component
{
    public $layanan_id, $kode_ruang, $status, $ruang;

    protected $rules = [
        'layanan_id' => 'required',
        'kode_ruang' => 'required|unique:ruangs,kode_ruang',
        // 'status' => 'required|in:tersedia,dipesan',
    ];

    public function mount()
    {
        $this->ruang = Ruang::with('layanan')->get();
    }

    public function save()
    {
        $this->validate();
    
        $ruang = Ruang::create([
            'layanan_id' => $this->layanan_id,
            'kode_ruang' => $this->kode_ruang,
        ]);
    
        session()->flash('success', 'ruang berhasil disimpan.');
    
        $this->reset();
        $this->ruang = Ruang::with('layanan')->get(); // Refresh data
    
        return $this->redirect(request()->header('Referer'), navigate: true);
    }

    public $editingId = null;

    public function edit($id)
    {
        $ruang = Ruang::find($id);
        $this->editingId = $id;
        $this->layanan_id = $ruang->layanan_id;
        $this->kode_ruang = $ruang->kode_ruang;
    }

    public function update()
    {
        $this->rules['kode_ruang'] = 'required|unique:ruangs,kode_ruang,' . $this->editingId;
        $this->validate();

        $ruang = Ruang::find($this->editingId);
        $ruang->update([
            'layanan_id' => $this->layanan_id,
            'kode_ruang' => $this->kode_ruang,
        ]);

        session()->flash('success', 'Ruang berhasil diperbarui.');

        $this->reset();
        $this->ruang = Ruang::with('layanan')->get();

        return $this->redirect(request()->header('Referer'), navigate: true);
    }

    public function cancelEdit()
    {
        $this->reset();
    }
    
    public function delete($id)
    {
        Ruang::find($id)->delete();
        session()->flash('success', 'ruang berhasil dihapus.');
        
        $this->ruang = Ruang::with('layanan')->get(); // Refresh data
        
        return $this->redirect(request()->header('Referer'), navigate: true);
    }

    public function render()
    {
        $layanan = Layanan::all();
        return view('livewire.admin.ruangan', compact('layanan'));
    }
}
