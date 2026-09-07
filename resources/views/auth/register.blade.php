@extends('layouts.auth')

@section('title', 'Daftar - TemJi')

@section('content')
    <h1 class="mb-1 text-center font-display text-xl font-bold">Daftar sebagai warga</h1>
    <p class="mb-6 text-center text-sm text-on-surface-variant">Registrasi publik hanya untuk role Resident</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="form-label">Nama Lengkap</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                   class="form-control @error('name') input-error @enderror">
            @error('name')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                   class="form-control @error('email') input-error @enderror">
            @error('email')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="phone" class="form-label">No. HP (opsional)</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                   class="form-control @error('phone') input-error @enderror">
            @error('phone')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="address" class="form-label">Alamat (opsional)</label>
            <textarea id="address" name="address" rows="2"
                      class="form-control @error('address') input-error @enderror">{{ old('address') }}</textarea>
            @error('address')
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
            <div class="form-text">Minimal 8 karakter.</div>
        </div>

        <div>
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required
                   class="form-control">
        </div>

        <button type="submit" class="btn btn-primary w-full">Daftar</button>
    </form>

    <p class="mt-5 text-center text-sm text-slate-600">
        Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-brand-600 hover:underline">Login di sini</a>
    </p>
@endsection
