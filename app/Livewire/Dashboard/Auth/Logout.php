<?php

namespace App\Livewire\Dashboard\Auth;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

use Livewire\Component;

use Illuminate\Support\Facades\Auth;

class Logout extends Component
{
    public function logout()
    {
        Auth::logout();
        Session::invalidate();
        Session::regenerateToken();
        return Response::redirectToRoute('dashboard.login');
    }

}
