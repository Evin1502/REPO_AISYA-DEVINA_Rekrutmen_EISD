@extends('layouts.auth')

@section('title', 'Login - TemJi')

@section('content')
    <div class="mb-6 flex flex-col items-center gap-2 text-center">
        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-50 text-brand-600">
            <span class="material-symbols-outlined text-2xl">waving_hand</span>
        </span>
        <h1 class="font-display text-xl font-bold">Masuk</h1>
        <p class="text-sm text-on-surface-variant">Masuk ke akun TemJi Anda</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                   class="form-control @error('email') input-error @enderror">
            @error('email')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="password" class="form-label">Password</label>
            <div class="relative">
                <input type="password" id="password" name="password" required
                       class="form-control pr-11 @error('password') input-error @enderror">
                <button type="button" onclick="const i=document.getElementById('password'); const s=this.querySelector('span'); i.type = i.type === 'password' ? 'text' : 'password'; s.textContent = i.type === 'password' ? 'visibility' : 'visibility_off';"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-on-surface-variant transition-colors hover:text-on-surface" aria-label="Tampilkan/sembunyikan password">
                    <span class="material-symbols-outlined text-lg">visibility</span>
                </button>
            </div>
            @error('password')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <label class="flex items-center gap-2 text-sm text-on-surface-variant">
            <input type="checkbox" name="remember" class="h-4 w-4 rounded border-outline-variant text-brand-600 focus:ring-brand-500">
            Ingat saya
        </label>

        <button type="submit" class="btn btn-primary w-full">
            Login
            <span class="material-symbols-outlined text-lg">login</span>
        </button>
    </form>

    <p class="mt-5 text-center text-sm text-on-surface-variant">
        Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-brand-600 hover:underline">Daftar di sini</a>
    </p>

    <div class="mt-6 flex items-start gap-2 rounded-lg bg-surface-container-low p-3 text-xs text-on-surface-variant">
        <span class="material-symbols-outlined mt-0.5 text-sm text-outline">info</span>
        <div>
            <p class="font-semibold text-on-surface">Akun demo (dari Seeder):</p>
            <p>Admin: admin@temji.test / admin12345</p>
            <p>Collector: collector1@temji.test / collector12345</p>
        </div>
    </div>
@endsection
