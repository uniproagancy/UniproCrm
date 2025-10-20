<?php

namespace App\Livewire\Dashboard\Admin;

use App\Models\Admin;
use App\Models\Role;
use App\Models\Verification;

use App\Services\SMSSenderService;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use Livewire\Component;

class Create extends Component
{
    public $name;
    public $lastname;
    public $email;
    public $phone;
    public $role_id;
    public $password;
    public $b_date;
    public $password_confirmation;

    public function render()
    {
        $role_list = Role::where('active', 1)->get();
        return view('livewire.dashboard.admin.create', compact('role_list'));
    }

    public function save()
    {
        $this->validate([
            'name'      => 'required',
            'lastname'  => 'required',
            'email'     => 'required|email|unique:db_admins,email',
            'phone'     => 'required|unique:db_admins,phone',
            'role_id'   => 'required|exists:db_roles,id',
            'b_date'   => 'required|date',
        ], [
            'required'       => 'გთხოვთ შეავსოთ ყველა აუცილებელი ველი!',
            'email.email'    => 'გთხოვთ შეიყვანეთ სწორი ელ-ფოსტა!',
            'email.unique'   => 'აღნიშნული ელ-ფოსტა უკვე დარეგისტრირებულია!',
            'phone.unique'   => 'აღნიშნული ტელეფონის ნომერი უკვე დარეგისტრირებულია!',
            'role_id.exists' => 'დაფიქსირდა შეცდომა!',
        ]);
        $password = Str::password(8, true, true, false);
        $admin = Admin::create([
            'name'     => $this->name,
            'lastname' => $this->lastname,
            'email'    => $this->email,
            'phone'    => $this->phone,
            'password' => Hash::make('Unipro123!!!'),
            'role_id'  => $this->role_id,
            'b_date'  => $this->b_date,
        ]);
        SMSSenderService::send($admin->phone, 'თქვენი დროებითი პაროლი: ' . $password);
        $verification_code = Str::random(40);
        Verification::create([
            'admin_id' => $admin->id,
            'type'     => 'email_verification',
            'value'    => $verification_code,
        ]);
        Mail::raw(
            'ვერიფიკაციის ბმული: ' . route('dashboard.email-verification', $verification_code),
            fn($message) => $message
                ->to($admin->email)
                ->subject('პროფილის აქტივაცია')
        );
        $this->reset();
        $this->dispatch('admin-created', message: 'ადმინისტრატორი წარმატებით დაემატა!');
        $this->dispatch('admin-refresh');
    }
}
