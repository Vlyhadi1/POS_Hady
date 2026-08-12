<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function auth(LoginRequest $request)
    {
        if (Auth::attempt($request->validated())) {

            $request->session()->regenerate();

            return redirect()->route('dashboard')
                ->with('success', 'Selamat Datang, ' . Auth::user()->name);
        }

        return back()->withErrors([
            'email' => 'Email atau password tidak valid'
        ]);
    }

    public function logout(Request $request) // <-- diperbaiki di sini
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah keluar aplikasi!');
    }
}