<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }
    
    public function login(Request $request)
    {
        // Lógica de autenticación aquí
    }
    
    public function showRegistrationForm()
    {
        return view('auth.register');
    }
}