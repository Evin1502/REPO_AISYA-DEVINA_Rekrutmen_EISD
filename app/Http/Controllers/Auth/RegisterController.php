<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Registrasi publik HANYA untuk role Resident.
     * Akun Admin & Collector dibuat manual lewat Seeder (lihat database/seeders/),
     * sesuai batasan hak akses pada studi kasus TemJi.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            // Otomatis di-hash oleh cast 'hashed' pada Model User, tidak perlu Hash::make manual.
            'password' => $validated['password'],
            'role' => 'resident',
        ]);

        Auth::login($user);

        return redirect()->route('resident.dashboard')
            ->with('success', 'Registrasi berhasil! Selamat datang di TemJi, ' . $user->name . '.');
    }
}
