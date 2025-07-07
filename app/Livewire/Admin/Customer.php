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
    public function render()
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'customer');
        })->get();

        return view('livewire.admin.customer', compact('users'));
    }
}
