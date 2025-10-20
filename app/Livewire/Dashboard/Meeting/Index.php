<?php

namespace App\Livewire\Dashboard\Meeting;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.dashboard.meeting.index')->layout('components.dashboard.layout');
    }
}
