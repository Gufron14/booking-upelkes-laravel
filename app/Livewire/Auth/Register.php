<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

#[Title('Register | Upelkes Jabar')]

class Register extends Component
{
    use WithFileUploads;
    
    public $nama = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $no_hp = '';
    public $alamat = '';
    public $foto_id_card;

    protected $rules = [
        'nama' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
        'password_confirmation' => 'required',
        'no_hp' => 'required|string|unique:users,no_hp',
        'alamat' => 'required|string',
        'foto_id_card' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ];

    protected $messages = [
        'nama.required' => 'Nama wajib diisi.',
        'nama.max' => 'Nama maksimal 255 karakter.',
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'email.unique' => 'Email sudah terdaftar.',
        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password minimal 6 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
        'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
        'no_hp.unique' => 'Nomor HP sudah terdaftar.',
        'foto_id_card.required' => 'Foto KTP wajib diisi.',
        'foto_id_card.image' => 'File harus berupa gambar.',
        'foto_id_card.mimes' => 'Foto harus berformat jpeg, png, atau jpg.',
        'foto_id_card.max' => 'Ukuran foto maksimal 2MB.',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function register()
    {
        $this->validate();

        try {
            $fotoIdCardPath = null;
            if ($this->foto_id_card) {
                $fotoIdCardPath = $this->foto_id_card->store('foto-id-cards', 'public');
            }

            $user = User::create([
                'nama' => $this->nama,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'no_hp' => $this->no_hp,
                'alamat' => $this->alamat,
                'foto_id_card' => $fotoIdCardPath,
            ]);

            $user->assignRole('customer');

            session()->flash('success', 'Registrasi berhasil! Selamat datang.');
            return redirect()->route('login');

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat registrasi. Silakan coba lagi.');
        }
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
