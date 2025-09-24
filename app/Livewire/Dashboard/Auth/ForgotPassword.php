<?php

namespace App\Livewire\Dashboard\Auth;

use App\Models\Admin;
use App\Models\Verification;
use App\Repositories\VerificationRepository;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use Livewire\Component;

class ForgotPassword extends Component
{
    public $email;

    public function render()
    {
        return view('livewire.dashboard.auth.forgot-password')
            ->layout('components.dashboard.layout', [
                'blank_page' => true
            ]);
    }

    public function sendResetLink()
    {
        $this->validate([
            'email' => 'required|email|exists:db_admins,email',
        ]);
        $admin = Admin::where('email', $this->email)->first();
        $verification_code = Str::random(40);
        Verification::where([
            'admin_id' => $admin->id,
            'type' => 'reset_password',
        ])->forceDelete();
        Verification::create([
            'admin_id' => $admin->id,
            'type' => 'reset_password',
            'value' => $verification_code,
        ]);
        Mail::raw('აღდგენის ბმული: '. route('dashboard.reset-password', $verification_code), function ($message) use ($admin) {
            $message->to($admin->email)->subject('პაროლის აღდგენა');
        });
        $this->dispatch('reset-link-sent', message: 'აღდგენის ბმული გაგზავნილია ელ-ფოსტაზე!');
    }
}
