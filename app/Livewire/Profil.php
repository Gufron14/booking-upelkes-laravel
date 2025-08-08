<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

#[Title('Profil')]
class Profil extends Component
{
    use WithFileUploads;

    public $nama, $email, $no_hp, $alamat, $foto_id_card;

    public function mount()
    {
        $user = Auth::user();
        $this->nama = $user->nama;
        $this->email = $user->email;
        $this->no_hp = $user->no_hp;
        $this->alamat = $user->alamat;
        $this->foto_id_card = $user->foto_id_card;
    }

    public function updateProfil()
    {
        $user = Auth::user();

        if ($this->foto_id_card) {
            $this->validate([
                'foto_id_card' => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);
            $user->update([
                'foto_id_card' => $this->foto_id_card->store('foto_id_card', 'public'),
            ]);
        }

        $this->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20|unique:users,no_hp,' . $user->id,
            'alamat' => 'nullable|string',
        ]);
        $user->update([
            'nama' => $this->nama,
            'no_hp' => $this->no_hp,
            'alamat' => $this->alamat,
        ]);
        session()->flash('success', 'Profil berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.profil', [
            'user' => Auth::user()
        ]);
    }
}