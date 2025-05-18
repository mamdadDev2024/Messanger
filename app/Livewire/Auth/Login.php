<?php

namespace App\Livewire\Auth;

use App\Actions\Auth\Login as AuthLogin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class Login extends Component
{
    #[Validate('required|email|exists:users,email')]
    public string $email;

    #[Validate('required|min:6|max:100|string')]
    public string $password;

    public function login()
    {
        $data = $this->validate();
        if (AuthLogin::attempt($data))
        {
            Toaster::success('خوش آمدید');
            return $this->redirectRoute('home', navigate: true);
        } else {
            throw ValidationException::withMessages([
                'email' => ['ایمیل یا رمز عبور اشتباه است.'],
            ]);
        }

    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
