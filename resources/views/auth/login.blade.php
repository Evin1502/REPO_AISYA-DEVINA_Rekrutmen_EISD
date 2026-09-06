@extends('layouts.auth')

@section('title', 'Login - TemJi')

@section('content')
    <h1 class="mb-1 text-center text-xl font-bold text-slate-900">Login</h1>
    <p class="mb-6 text-center text-sm text-slate-500">Masuk ke akun TemJi Anda</p>

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

        <label class="flex items-center gap-2 text-sm text-slate-600">
            <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500">
            Ingat saya
        </label>

        <button type="submit" class="btn btn-primary w-full">Login</button>
    </form>

    <p class="mt-5 text-center text-sm text-slate-600">
        Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-brand-600 hover:underline">Daftar di sini</a>
    </p>

    <div class="mt-6 rounded-lg bg-slate-50 p-3 text-xs text-slate-500">
        <p class="font-semibold text-slate-600">Akun demo (dari Seeder):</p>
        <p>Admin: admin@temji.test / admin12345</p>
        <p>Collector: collector1@temji.test / collector12345</p>
    </div>
@endsection
