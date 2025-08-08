<?php

namespace App\Livewire\Admin;

use App\Models\Ruang;
use App\Models\Layanan;
use App\Models\GambarRuang;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;

#[Title('Kelola Ruangan')]
#[Layout('components.layouts.admin-layout')]
class Ruangan extends Component
{
    use WithFileUploads;

    public $layanan_id, $kode_ruang, $status, $ruang;
    public $images = [];
    public $editingId = null;

    protected $rules = [
        'layanan_id' => 'required',
        'kode_ruang' => 'required|unique:ruangs,kode_ruang',
        // 'status' => 'required|in:tersedia,dipesan',
    ];

    public function mount()
    {
        $this->ruang = Ruang::with(['layanan', 'gambarRuangs'])->get();
    }

    public function save()
    {
        $this->validate();

        $ruang = Ruang::create([
            'layanan_id' => $this->layanan_id,
            'kode_ruang' => $this->kode_ruang,
        ]);

        // Save images
        foreach ($this->images as $image) {
            $path = $image->store('ruang-images', 'public');
            GambarRuang::create([
                'ruang_id' => $ruang->id,
                'path' => $path
            ]);
        }

        session()->flash('success', 'ruang berhasil disimpan.');

        $this->reset();
        $this->ruang = Ruang::with(['layanan', 'gambarRuangs'])->get(); // Refresh data
        $this->dispatch('hideRuangModal');
    }

    public function edit($id)
    {
        $ruang = Ruang::find($id);
        $this->editingId = $id;
        $this->layanan_id = $ruang->layanan_id;
        $this->kode_ruang = $ruang->kode_ruang;
        $this->dispatch('showRuangModal');
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

        // Update images if new ones are uploaded
        if (!empty($this->images)) {
            foreach ($this->images as $image) {
                $path = $image->store('ruang-images', 'public');
                GambarRuang::create([
                    'ruang_id' => $ruang->id,
                    'path' => $path
                ]);
            }
        }

        session()->flash('success', 'Ruang berhasil diperbarui.');

        $this->reset();
        $this->ruang = Ruang::with(['layanan', 'gambarRuangs'])->get();
        $this->dispatch('hideRuangModal');
    }

    public function openModal()
    {
        $this->reset(['editingId', 'layanan_id', 'kode_ruang', 'images']);
        $this->dispatch('showRuangModal');
    }

    public function closeModal()
    {
        $this->reset();
        $this->dispatch('hideRuangModal');
    }

    public function cancelEdit()
    {
        $this->reset();
    }

    public function delete($id)
    {
        Ruang::find($id)->delete();
        session()->flash('success', 'ruang berhasil dihapus.');

        $this->ruang = Ruang::with(['layanan', 'gambarRuangs'])->get(); // Refresh data

        return $this->redirect(request()->header('Referer'), navigate: true);
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
            $this->ruang = Ruang::with(['layanan', 'gambarRuangs'])->get();

            session()->flash('success', 'Gambar berhasil dihapus.');
        }
    }

    public function render()
    {
        $layanan = Layanan::where('jenis', 'ruangan')->get();
        return view('livewire.admin.ruangan', compact('layanan'));
    }
}