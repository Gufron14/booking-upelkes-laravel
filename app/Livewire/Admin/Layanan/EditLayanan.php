<?php

namespace App\Livewire\Admin\Layanan;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Edit Layanan')]
#[Layout('components.layouts.admin-layout')]

class EditLayanan extends Component
{
    public function render()
    {
        return view('livewire.admin.layanan.edit-layanan');
    }
}
