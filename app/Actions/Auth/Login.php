<?php

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Auth;
use Masmerise\Toaster\Toaster;

class Login
{
    public static function attempt(array $credentials): bool
    {
        return Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ], true);
    }
}
