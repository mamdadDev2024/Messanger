<?php

namespace App\Livewire\Auth;

use App\Actions\Auth\Register as AuthRegister;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class Register extends Component
{
    #[Validate('required|email|unique:users')]
    public string $email;
    #[Validate('required|min:6|max:100|string|confirmed')]
    public string $password;
    public string $password_confirmation;
    public function render()
    {
        return view('livewire.auth.register');
    }

    public function register()
    {
        $data = $this->validate();

        if (AuthRegister::register($data)){
            Toaster::success('خوش آمدید');
            return $this->redirectRoute('home' , navigate:true);
        }
        else {
            Toaster::error('مشکلی در ثبت نام رخ داده');
        }   
    }
}
