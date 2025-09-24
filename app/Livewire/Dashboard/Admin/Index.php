<?php

namespace App\Livewire\Dashboard\Admin;

use App\Models\Admin;

use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{

    use WithPagination;

    public function render()
    {
        return view('livewire.dashboard.admin.index')->layout('components.dashboard.layout');
    }
}
