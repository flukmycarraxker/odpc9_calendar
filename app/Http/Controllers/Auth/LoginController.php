<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    
    public function username()
    {
        return 'username';
    }

   
    protected $redirectTo = '/index';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    
    
}

