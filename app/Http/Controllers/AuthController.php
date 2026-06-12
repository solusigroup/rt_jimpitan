<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session('superuser')) {
            return redirect()->route('admin.warga');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $adminPassword = env('RT_ADMIN_PASSWORD', 'pengurusRT35');

        if ($request->password === $adminPassword) {
            session(['superuser' => true]);
            return redirect()->route('admin.warga');
        }

        return back()->withErrors(['password' => 'Kata sandi salah! Silakan coba lagi.']);
    }

    public function logout()
    {
        session()->forget('superuser');
        return redirect()->route('home');
    }
}
