<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

#[Title('Register | Upelkes Jabar')]

class Register extends Component
{
    public $nama = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $no_hp = '';
    public $alamat = '';

    protected $rules = [
        'nama' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
        'password_confirmation' => 'required',
        'no_hp' => 'nullable|string|unique:users,no_hp',
        'alamat' => 'nullable|string',
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
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function register()
    {
        $this->validate();

        try {
            $user = User::create([
                'nama' => $this->nama,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'no_hp' => $this->no_hp,
                'alamat' => $this->alamat,
            ]);

            session()->flash('success', 'Registrasi berhasil! Selamat datang.');
            return redirect()->route('/');

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat registrasi. Silakan coba lagi.');
        }
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
