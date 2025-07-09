<?php

namespace App\Livewire\Admin;

use App\Models\Layanan;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\Kamar as KamarModel;

#[Title('Kelola Kamar')]
#[Layout('components.layouts.admin-layout')]
class Kamar extends Component
{
    public $layanan_id, $nomor_kamar, $status, $kamar;

    protected $rules = [
        'layanan_id' => 'required',
        'nomor_kamar' => 'required|unique:kamars,nomor_kamar',
        // 'status' => 'required|in:tersedia,dipesan',
    ];

    public function mount()
    {
        $this->kamar = KamarModel::with('layanan')->get();
    }

    public function save()
    {
        $this->validate();

        $kamar = KamarModel::create([
            'layanan_id' => $this->layanan_id,
            'nomor_kamar' => $this->nomor_kamar,
        ]);

        session()->flash('success', 'Kamar berhasil disimpan.');

        $this->reset();
        $this->kamar = KamarModel::with('layanan')->get(); // Refresh data

        return $this->redirect(request()->header('Referer'), navigate: true);
    }

    public function delete($id)
    {
        KamarModel::find($id)->delete();
        session()->flash('success', 'Kamar berhasil dihapus.');

        $this->kamar = KamarModel::with('layanan')->get(); // Refresh data

        return $this->redirect(request()->header('Referer'), navigate: true);
    }

    public function render()
    {
        $layanan = Layanan::all();
        return view('livewire.admin.kamar', compact('layanan'));
    }

    public $editingId = null;

    public function edit($id)
    {
        $kamar = KamarModel::find($id);
        $this->editingId = $id;
        $this->layanan_id = $kamar->layanan_id;
        $this->nomor_kamar = $kamar->nomor_kamar;
    }

    public function update()
    {
        $this->rules['nomor_kamar'] = 'required|unique:kamars,nomor_kamar,' . $this->editingId;
        $this->validate();

        $kamar = KamarModel::find($this->editingId);
        $kamar->update([
            'layanan_id' => $this->layanan_id,
            'nomor_kamar' => $this->nomor_kamar,
        ]);

        session()->flash('success', 'Kamar berhasil diperbarui.');

        $this->reset();
        $this->kamar = KamarModel::with('layanan')->get();

        return $this->redirect(request()->header('Referer'), navigate: true);
    }

    public function cancelEdit()
    {
        $this->reset();
    }
}
