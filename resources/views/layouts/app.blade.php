<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TemJi')</title>
    @include('layouts.partials.head-assets')
</head>
<body class="bg-slate-50 text-slate-900">
    @php
        $current = request()->route()?->getName();
    @endphp

    <div class="min-h-screen lg:flex">
        {{-- Mobile top bar --}}
        <div class="sticky top-0 z-30 flex items-center justify-between bg-brand-600 px-4 py-3 text-white lg:hidden">
            <a href="{{ route('home') }}" class="font-bold">🗑️ TemJi</a>
            <button id="hamburger" class="text-white" aria-label="Menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        {{-- Sidebar --}}
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col bg-brand-700 transition-transform duration-200 lg:static lg:translate-x-0">
            <div class="flex h-16 items-center gap-2 border-b border-white/10 px-5">
                <a href="{{ route('home') }}" class="text-lg font-bold text-white">🗑️ TemJi</a>
                <span class="rounded-full bg-white/20 px-2 py-0.5 text-xs font-semibold text-white">Resident</span>
            </div>

            <div class="flex-1 px-3 py-4">
                <p class="px-3 pb-2 text-xs font-semibold uppercase tracking-wider text-brand-200">Menu</p>
                <nav class="space-y-1">
                    <a href="{{ route('resident.dashboard') }}"
                       class="sidebar-link {{ str_starts_with($current, 'resident.dashboard') ? 'sidebar-link-active bg-brand-600' : 'sidebar-link-inactive' }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10"/></svg>
                        Beranda
                    </a>
                    <a href="{{ route('resident.pickup-requests.index') }}"
                       class="sidebar-link {{ str_starts_with($current, 'resident.pickup-requests') ? 'sidebar-link-active bg-brand-600' : 'sidebar-link-inactive' }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        Pengajuan
                    </a>
                    <a href="{{ route('resident.point-histories.index') }}"
                       class="sidebar-link {{ str_starts_with($current, 'resident.point-histories') ? 'sidebar-link-active bg-brand-600' : 'sidebar-link-inactive' }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        Riwayat Poin
                    </a>
                    <a href="{{ route('resident.rewards.index') }}"
                       class="sidebar-link {{ str_starts_with($current, 'resident.rewards') ? 'sidebar-link-active bg-brand-600' : 'sidebar-link-inactive' }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V6a2 2 0 00-2 2h2z"/></svg>
                        Tukar Poin
                    </a>
                    <a href="{{ route('resident.news.index') }}"
                       class="sidebar-link {{ str_starts_with($current, 'resident.news') ? 'sidebar-link-active bg-brand-600' : 'sidebar-link-inactive' }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        Berita
                    </a>
                    <a href="{{ route('resident.notifications.index') }}"
                       class="sidebar-link {{ str_starts_with($current, 'resident.notifications') ? 'sidebar-link-active bg-brand-600' : 'sidebar-link-inactive' }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        Notifikasi
                        @if (auth()->user()->unreadNotificationsCount())
                            <span class="ml-auto rounded-full bg-white/20 px-2 text-xs">{{ auth()->user()->unreadNotificationsCount() }}</span>
                        @endif
                    </a>
                </nav>
            </div>
        </aside>

        {{-- Overlay for mobile --}}
        <div id="overlay" class="fixed inset-0 z-30 hidden bg-black/50 lg:hidden"></div>

        {{-- Main content --}}
        <div class="min-w-0 flex-1">
            {{-- Topbar --}}
            <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-slate-200 bg-white px-4 lg:px-8">
                <div>
                    <h1 class="text-lg font-bold text-slate-900">@yield('title', 'Dashboard')</h1>
                </div>
                <div class="flex items-center gap-3">
                    <span class="hidden items-center gap-1 rounded-full bg-brand-50 px-3 py-1 text-sm font-semibold text-brand-700 sm:inline-flex">
                        ⭐ {{ auth()->user()->points }} poin
                    </span>
                    <span class="hidden items-center gap-1 rounded-full bg-green-50 px-3 py-1 text-sm font-semibold text-green-700 sm:inline-flex">
                        💰 {{ auth()->user()->cashBalanceLabel() }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-secondary btn-sm">Logout</button>
                    </form>
                </div>
            </header>

            <main class="p-4 lg:p-8">
                @if (session('success'))
                    <x-alert type="success">{{ session('success') }}</x-alert>
                @endif

                @if (session('error'))
                    <x-alert type="error">{{ session('error') }}</x-alert>
                @endif

                @if ($errors->any())
                    <x-alert type="error">
                        <strong>Terjadi kesalahan input:</strong>
                        <ul class="mt-1 list-disc pl-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-alert>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @include('layouts.partials.sidebar-toggle')

    @stack('scripts')
</body>
</html>
