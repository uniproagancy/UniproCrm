<?php

namespace App\Livewire\Dashboard\Order;

use Livewire\Component;

class Index extends Component
{

    public function render()
    {
        return view('livewire.dashboard.order.index')->layout('components.dashboard.layout');
    }
}
