<?php

namespace App\Livewire\Admin;

use App\Models\Layanan;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\Kamar as KamarModel;
use App\Models\GambarRuang;
use Livewire\WithFileUploads;

#[Title('Kelola Kamar')]
#[Layout('components.layouts.admin-layout')]
class Kamar extends Component
{
    use WithFileUploads;

    public $layanan_id, $nomor_kamar, $status, $kamar;
    public $showModal = false;
    public $images = [];

    protected $rules = [
        'layanan_id' => 'required',
        'nomor_kamar' => 'required|unique:kamars,nomor_kamar',
        // 'status' => 'required|in:tersedia,dipesan',
    ];

    public function mount()
    {
        $this->kamar = KamarModel::with(['layanan', 'gambarRuangs'])->get();
    }

    public function save()
    {
        $this->validate();

        $kamar = KamarModel::create([
            'layanan_id' => $this->layanan_id,
            'nomor_kamar' => $this->nomor_kamar,
        ]);

        // Save images
        foreach ($this->images as $image) {
            $path = $image->store('kamar-images', 'public');
            GambarRuang::create([
                'kamar_id' => $kamar->id,
                'path' => $path
            ]);
        }

        session()->flash('success', 'Kamar berhasil disimpan.');
        $this->reset();
        $this->kamar = KamarModel::with(['layanan', 'gambarRuangs'])->get();
        $this->dispatch('hideKamarModal');
    }

    public function openModal()
    {
        $this->reset(['editingId', 'layanan_id', 'nomor_kamar', 'images']);
        $this->dispatch('showKamarModal');
    }

    public function closeModal()
    {
        $this->reset();
        $this->dispatch('hideKamarModal');
    }

    public function delete($id)
    {
        KamarModel::find($id)->delete();
        session()->flash('success', 'Kamar berhasil dihapus.');

        $this->kamar = KamarModel::with(['layanan', 'gambarRuangs'])->get(); // Refresh data

        return $this->redirect(request()->header('Referer'), navigate: true);
    }

    public function render()
    {
        $layanan = Layanan::where('jenis', 'kamar')->get();
        return view('livewire.admin.kamar', compact('layanan'));
    }

    public $editingId = null;

    public function edit($id)
    {
        $kamar = KamarModel::find($id);
        $this->editingId = $id;
        $this->layanan_id = $kamar->layanan_id;
        $this->nomor_kamar = $kamar->nomor_kamar;
        $this->images = $kamar->gambarRuangs->pluck('path')->toArray();
        $this->dispatch('showKamarModal');
    }

    public function update()
    {
        $this->rules['nomor_kamar'] = 'required|unique:kamars,nomor_kamar,' . $this->editingId;
        $this->validate();

        // Delete old images
        GambarRuang::where('kamar_id', $this->editingId)->delete();
        // Save new images
        foreach ($this->images as $image) {
            $path = $image->store('kamar-images', 'public');
            GambarRuang::create([
                'kamar_id' => $this->editingId,
                'path' => $path
            ]);
        }

        $kamar = KamarModel::find($this->editingId);
        $kamar->update([
            'layanan_id' => $this->layanan_id,
            'nomor_kamar' => $this->nomor_kamar,

        ]);

        session()->flash('success', 'Kamar berhasil diperbarui.');

        $this->reset();
        $this->kamar = KamarModel::with(['layanan', 'gambarRuangs'])->get();
        $this->dispatch('hideKamarModal');
    }

    public function cancelEdit()
    {
        $this->reset();
    }

    public function deleteImage($imageId)
    {
        $image = GambarRuang::find($imageId);
        if ($image) {
            // Delete the actual file
            if (file_exists(storage_path('app/public/' . $image->path))) {
                unlink(storage_path('app/public/' . $image->path));
            }

            // Delete from database
            $image->delete();

            // Refresh data
            $this->kamar = KamarModel::with(['layanan', 'gambarRuangs'])->get();

            session()->flash('success', 'Gambar berhasil dihapus.');
        }
    }
}