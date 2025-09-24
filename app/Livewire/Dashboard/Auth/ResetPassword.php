<?php

namespace App\Livewire\Dashboard\Auth;

use App\Models\Admin;
use App\Models\Verification;

use Illuminate\Support\Facades\Hash;

use Livewire\Component;

class ResetPassword extends Component
{
    public $password;
    public $password_confirmation;
    public $value;

    public function render()
    {
        Verification::where([
            'type' => 'reset_password',
            'value' => $this->value,
        ])->firstOrFail();
        return view('livewire.dashboard.auth.reset-password')
            ->layout('components.dashboard.layout', [
                'blank_page' => true
        ]);
    }

    public function resetPassword()
    {
        $this->validate([
            'password' => 'required|string|min:8|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'required',
            'value' => 'exists:db_verifications,value',
        ], [
            'required' => 'გთხოვთ შეავსოთ ყველა აუცილებელი ველი!',
            'password.min' => 'პაროლი უნდა შედგებოდეს მინიმუმ 8 სიმბოლოსაგან!',
            'password.same' => 'პაროლის განმეორება არ ემთხვევა!',
            'value.exists' => 'აღდგენის ბმული არასწორია!',
        ]);
        $verification = Verification::where([
            'type' => 'reset_password',
            'value' => $this->value,
        ])->first();
        if(empty($verification)) {
            $this->dispatch('reset-error', message: 'აღდგენის ბმული არასწორია!');
        } else {
            Admin::find($verification->admin_id)->update([
                'password' => Hash::make($this->password),
            ]);
            Verification::where(['id' => $verification])->forceDelete();
        }
        $this->dispatch('reset-success', message: 'პაროლი წარმატებით განახლდა!');
    }
}
