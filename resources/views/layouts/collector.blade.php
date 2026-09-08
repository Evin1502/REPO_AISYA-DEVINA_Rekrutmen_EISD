<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kolektor - TemJi')</title>
    @include('layouts.partials.head-assets')
</head>
<body class="bg-surface font-sans text-on-surface">
    @php
        $current = request()->route()?->getName();
        $user = auth()->user();
        $queueCount = $user->assignedPickups()->whereIn('status', ['approved', 'scheduled'])->count();
    @endphp

    <div class="min-h-screen lg:flex">
        <div class="sticky top-0 z-30 flex items-center justify-between border-b border-outline-variant/40 bg-surface-container-low px-4 py-3 lg:hidden">
            <a href="{{ route('collector.dashboard') }}" class="flex items-center gap-2 font-display font-black text-on-surface">
                <span class="material-symbols-outlined text-collector-600">recycling</span> TemJi
            </a>
            <button id="hamburger" class="inline-flex h-11 w-11 items-center justify-center rounded-lg" type="button" aria-label="Menu">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </div>

        <aside id="sidebar" class="shell-sidebar">
            <div class="flex h-full flex-col justify-between">
                <div class="flex flex-col gap-6">
                    <div class="flex items-center gap-3 px-1">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-collector-600 text-white shadow-sm">
                            <span class="material-symbols-outlined text-xl">recycling</span>
                        </div>
                        <div class="flex flex-col">
                            <div class="flex items-center gap-2">
                                <span class="font-display text-base font-bold tracking-tight">TemJi</span>
                                <span class="rounded-full bg-collector-50 px-1.5 py-0.5 text-[10px] font-semibold text-collector-700">Kolektor</span>
                            </div>
                            <span class="text-xs text-on-surface-variant">Lapangan</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between rounded-xl border border-outline-variant/30 bg-surface-container-lowest p-3">
                        <div class="flex min-w-0 items-center gap-2.5">
                            <x-user-initials :name="$user->name" class="h-8 w-8" />
                            <div class="min-w-0">
                                <span class="block truncate text-xs font-semibold">{{ $user->name }}</span>
                                <span class="truncate text-[11px] text-on-surface-variant">Mitra kolektor</span>
                            </div>
                        </div>
                        <div class="flex shrink-0 items-center gap-1.5 rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-medium text-emerald-800">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>Aktif
                        </div>
                    </div>
                    <nav class="flex flex-col gap-1 text-sm font-medium">
                        <a href="{{ route('collector.dashboard') }}" class="sidebar-link {{ $current === 'collector.dashboard' ? 'sidebar-link-active-collector' : 'sidebar-link-inactive' }}">
                            <span class="material-symbols-outlined text-lg">dashboard</span> Ringkasan
                        </a>
                        <a href="{{ route('collector.pickup-requests.index') }}" class="sidebar-link {{ str_starts_with($current, 'collector.pickup-requests') ? 'sidebar-link-active-collector' : 'sidebar-link-inactive' }}">
                            <span class="material-symbols-outlined text-lg">local_shipping</span>
                            <span class="flex-1">Penjemputan aktif</span>
                            @if ($queueCount)
                                <span class="rounded-full {{ $current === 'collector.dashboard' || str_starts_with($current, 'collector.pickup-requests') ? 'bg-white/20' : 'bg-collector-50 text-collector-700' }} px-1.5 text-xs font-bold">{{ $queueCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('collector.history') }}" class="sidebar-link {{ $current === 'collector.history' ? 'sidebar-link-active-collector' : 'sidebar-link-inactive' }}">
                            <span class="material-symbols-outlined text-lg">receipt_long</span> Riwayat &amp; laporan
                        </a>
                    </nav>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="border-t border-outline-variant/30 pt-3">
                    @csrf
                    <button type="submit" class="sidebar-link sidebar-link-inactive w-full">
                        <span class="material-symbols-outlined">logout</span> Keluar
                    </button>
                </form>
            </div>
        </aside>

        <div id="overlay" class="fixed inset-0 z-30 hidden bg-forest/45 backdrop-blur-[2px] lg:hidden"></div>

        <div class="min-w-0 flex-1">
            <header class="sticky top-0 z-20 hidden h-16 items-center justify-between border-b border-outline-variant/40 bg-surface/90 px-4 backdrop-blur-md lg:flex lg:px-8">
                <div>
                    <h1 class="font-display text-lg font-black tracking-tight">@yield('title', 'Kolektor')</h1>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2 rounded-lg border border-outline-variant/30 bg-surface-container-lowest px-3 py-1 shadow-sm">
                        <span class="material-symbols-outlined text-base text-amber-600">payments</span>
                        <div class="text-right">
                            <span class="block text-[10px] leading-none text-on-surface-variant">Selesai dijemput</span>
                            <span class="text-xs font-bold">{{ $user->assignedPickups()->where('status', 'collected')->count() }} tugas</span>
                        </div>
                    </div>
                    <x-user-initials :name="$user->name" class="h-7 w-7" />
                </div>
            </header>

            <main class="p-4 lg:p-8">
                @include('layouts.partials.flash')
                @yield('content')
            </main>
        </div>
    </div>

    @include('layouts.partials.sidebar-toggle')
    @stack('scripts')
</body>
</html>
