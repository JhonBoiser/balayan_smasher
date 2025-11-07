<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Handle redirection after login based on user role.
     */
    protected function redirectTo()
    {
        if (auth()->user()->role === 'admin') {
            return route('admin.dashboard');
        }

        return route('home');
    }
}
