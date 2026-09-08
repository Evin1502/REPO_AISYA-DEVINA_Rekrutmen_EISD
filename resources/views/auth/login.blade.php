@extends('layouts.auth')

@section('title', 'Login - TemJi')

@section('content')
    <h1 class="mb-1 text-center font-display text-xl font-bold">Masuk</h1>
    <p class="mb-6 text-center text-sm text-on-surface-variant">Masuk ke akun TemJi Anda</p>

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
            <input type="password" id="password" name="password" required
                   class="form-control @error('password') input-error @enderror">
            @error('password')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <label class="flex items-center gap-2 text-sm text-on-surface-variant">
            <input type="checkbox" name="remember" class="h-4 w-4 rounded border-outline-variant text-brand-600 focus:ring-brand-500">
            Ingat saya
        </label>

        <button type="submit" class="btn btn-primary w-full">Login</button>
    </form>

    <p class="mt-5 text-center text-sm text-on-surface-variant">
        Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-brand-600 hover:underline">Daftar di sini</a>
    </p>

    <div class="mt-6 rounded-lg bg-surface-container-low p-3 text-xs text-on-surface-variant">
        <p class="font-semibold text-on-surface">Akun demo (dari Seeder):</p>
        <p>Admin: admin@temji.test / admin12345</p>
        <p>Collector: collector1@temji.test / collector12345</p>
    </div>
@endsection
