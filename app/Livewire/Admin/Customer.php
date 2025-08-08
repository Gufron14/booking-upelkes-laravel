<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Daftar Customer')]
#[Layout('components.layouts.admin-layout')]

class Customer extends Component
{
    public $search = '';
    public $date_from = null;
    public $date_to = null;
    // Fungsi Hapus Customer

    public $searchTerm = '';

    public function searchCustomer()
    {
        $this->validate([
            'searchTerm' => 'required|min:3'
        ]);

        $this->users = User::whereHas('roles', function ($query) {
            $query->where('name', 'customer');
        })
            ->where(function ($q) {
                $q->where('nama', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('nama_instansi', 'like', '%' . $this->searchTerm . '%');
            })
            ->get();
    }

    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->date_from = null;
        $this->date_to = null;
    }


    public function render()
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'customer');
        })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('nama_instansi', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->date_from, function ($query) {
                $query->whereDate('created_at', '>=', $this->date_from);
            })
            ->when($this->date_to, function ($query) {
                $query->whereDate('created_at', '<=', $this->date_to);
            })
            ->get();

        return view('livewire.admin.customer', compact('users'));
    }
}