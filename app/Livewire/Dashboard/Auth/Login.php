<?php

namespace App\Livewire\Dashboard\Auth;

use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\Attributes\Layout;

use Illuminate\Support\Facades\Hash;

class Login extends Component
{

    public $email;
    public $password;
    public $remember_me = false;

    public function render()
    {
        return view('livewire.dashboard.auth.login')
        ->layout('components.dashboard.layout', [
            'blank_page' => true
        ]);
    }

    public function login()
    {
        $this->validate([
            'email' => 'required|email|exists:db_admins,email',
            'password' => 'required',
        ], [
            'email.required' => 'ელ-ფოსტა სავალდებულოა!',
            'email.email' => 'გთხოვთ შეიყვანეთ სწორი ელ-ფოსტა!',
            'exists' => 'ელ-ფოსტა ვერ მოიძებნა!',
            'password.required' => 'პაროლი სავალდებულოა!',
        ]);
        if(auth()->attempt(['email' => $this->email, 'password' => $this->password], $this->remember_me)){
            return Response::redirectToRoute('dashboard.index');
        }
        $this->dispatch('login-error', message: 'ელ-ფოსტა ან პაროლი არასწორია!');
    }
}
