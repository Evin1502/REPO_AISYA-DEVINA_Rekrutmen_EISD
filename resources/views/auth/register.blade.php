@extends('layouts.auth')

@section('title', 'Daftar - TemJi')

@section('content')
    <div class="mb-6 flex flex-col items-center gap-2 text-center">
        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-50 text-brand-600">
            <span class="material-symbols-outlined text-2xl">person_add</span>
        </span>
        <h1 class="font-display text-xl font-bold">Daftar sebagai warga</h1>
        <p class="text-sm text-on-surface-variant">Registrasi publik hanya untuk role Resident</p>
    </div>

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
            <div class="form-text">Minimal 8 karakter.</div>
        </div>

        <div>
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <div class="relative">
                <input type="password" id="password_confirmation" name="password_confirmation" required
                       class="form-control pr-11">
                <button type="button" onclick="const i=document.getElementById('password_confirmation'); const s=this.querySelector('span'); i.type = i.type === 'password' ? 'text' : 'password'; s.textContent = i.type === 'password' ? 'visibility' : 'visibility_off';"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-on-surface-variant transition-colors hover:text-on-surface" aria-label="Tampilkan/sembunyikan password">
                    <span class="material-symbols-outlined text-lg">visibility</span>
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-full">
            Daftar
            <span class="material-symbols-outlined text-lg">arrow_forward</span>
        </button>
    </form>

    <p class="mt-5 text-center text-sm text-on-surface-variant">
        Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-brand-600 hover:underline">Login di sini</a>
    </p>
@endsection
