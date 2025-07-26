<?php
namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;

#[Title('Profil')]
class Profil extends Component
{
    public $nama, $email, $no_hp, $alamat;

    public function mount()
    {
        $user = Auth::user();
        $this->nama = $user->nama;
        $this->email = $user->email;
        $this->no_hp = $user->no_hp;
        $this->alamat = $user->alamat;
    }

    public function updateProfil()
    {
        $user = Auth::user();
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
        return view('livewire.profil');
    }
}