<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect($this->redirectPath())
                ->with('success', 'Login berhasil, selamat datang ' . Auth::user()->name . '!');
        }

        return back()
            ->withErrors(['email' => 'Email atau password yang kamu masukkan salah.'])
            ->onlyInput('email');
    }

    /**
     * Arahkan user ke dashboard sesuai rolenya setelah login.
     */
    protected function redirectPath(): string
    {
        $user = Auth::user();

        return match ($user->role) {
            'admin' => route('admin.dashboard'),
            'collector' => route('collector.dashboard'),
            default => route('resident.dashboard'),
        };
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Kamu berhasil logout.');
    }
}
