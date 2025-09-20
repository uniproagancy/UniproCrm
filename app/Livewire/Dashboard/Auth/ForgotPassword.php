<?php

namespace App\Livewire\Dashboard\Auth;

use AllowDynamicProperties;
use App\Repositories\AdminRepository;
use App\Repositories\VerificationRepository;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

use Livewire\Component;

class ForgotPassword extends Component
{
    public $email;

    protected $adminRepository;

    public function boot(
        AdminRepository $adminRepository,
        VerificationRepository $verificationRepository
    )
    {
        $this->adminRepository = $adminRepository;
        $this->verificationRepository = $verificationRepository;
    }

    public function sendResetLink()
    {
        $this->validate([
            'email' => 'required|email|exists:db_admins,email',
        ]);
        $admin = $this->adminRepository->getItemByEmail($this->email);
        $this->verificationRepository->forceDelete([
            'admin_id' => $admin->id,
            'type' => 'reset_password',
        ]);
        $verification = $this->verificationRepository->create([
            'admin_id' => $admin->id,
            'type' => 'reset_password',
            'value' => Str::random(40),
        ]);
        Mail::raw('აღდგენის ბმული: '. route('dashboard.reset-password', $verification->value), function ($message) use ($admin) {
            $message->to($admin->email)->subject('პაროლის აღდგენა');
        });
        $this->dispatch('reset-link-sent', message: 'აღდგენის ბმული გაგზავნილია ელ-ფოსტაზე!');
    }
    public function render()
    {
        return view('livewire.dashboard.auth.forgot-password')
            ->layout('components.dashboard.layout', [
                'blank_page' => true
            ]);
    }
}
