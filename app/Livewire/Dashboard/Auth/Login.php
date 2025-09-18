<?php

namespace App\Livewire\Dashboard\Auth;

use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

use Illuminate\Support\Facades\Hash;

class Login extends Component
{

    public $email;
    public $password;

    #[Layout('livewire.dashboard.layout')]
    public function render()
    {
        return view('livewire.dashboard.auth.login');
    }

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if(auth()->attempt(['email' => $this->email, 'password' => $this->password])){
            return redirect()->route('dashboard.index');
        }
        session()->flash('error', 'Invalid credentials');
    }
}
