<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function showResetForm(Request $request, $token = null) // .. 1
    {
        if (is_null($token)) {
            return $this->getEmail();
        }

        $this->data['email'] = $request->input('email');
        $this->data['token'] = $token;

        return $this->load_theme('auth.password.reset', $this->data);
    }
}










// p: clue 1
// kita meng override method showResetForm() dari ResetsPasswords
// karena kita ingin mengganti form registernya

// copy ini untuk mengganti password dari user yg bersangkutan
// token nya ada di password_resets
// http://127.0.0.1:8000/password/reset/$2y$10$VybwEBEry2QgBzpLEA2MVOcLIfgSFEml8p1M3ALKm3k...
