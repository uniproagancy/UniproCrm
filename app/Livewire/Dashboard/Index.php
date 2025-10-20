<?php

namespace App\Livewire\Dashboard;

use App\Services\SSService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.dashboard.index')->layout('components.dashboard.layout');
    }
}
