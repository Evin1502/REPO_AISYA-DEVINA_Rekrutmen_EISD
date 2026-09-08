<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kolektor - TemJi')</title>
    @include('layouts.partials.head-assets')
</head>
<body class="bg-surface font-sans text-slate-900">
    @php
        $current = request()->route()?->getName();
        $user = auth()->user();
        $queueCount = $user->assignedPickups()->whereIn('status', ['approved', 'scheduled'])->count();
    @endphp

    <div class="min-h-screen lg:flex">
        <div class="sticky top-0 z-30 flex items-center justify-between border-b border-slate-200/80 bg-white px-4 py-3 lg:hidden">
            <a href="{{ route('collector.dashboard') }}" class="flex items-center gap-2 font-display font-black text-slate-900">
                <span class="material-symbols-outlined text-collector-600">recycling</span> TemJi
            </a>
            <button id="hamburger" class="inline-flex h-10 w-10 items-center justify-center rounded-xl hover:bg-slate-100" type="button" aria-label="Menu">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </div>

        <aside id="sidebar" class="shell-sidebar">
            <div class="flex h-full flex-col justify-between">
                <div class="flex flex-col gap-6">
                    <div class="flex items-center gap-3 px-1">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-collector-600 text-white shadow-sm shadow-collector-600/20">
                            <span class="material-symbols-outlined text-xl">recycling</span>
                        </div>
                        <div class="flex flex-col">
                            <div class="flex items-center gap-2">
                                <span class="font-display text-base font-bold tracking-tight text-slate-900">TemJi</span>
                                <span class="rounded-full bg-collector-50 border border-collector-200 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-collector-700">Kolektor</span>
                            </div>
                            <span class="text-xs font-medium text-slate-500">Operasional Lapangan</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between rounded-2xl border border-amber-200/80 bg-amber-50/50 p-3">
                        <div class="flex min-w-0 items-center gap-2.5">
                            <x-user-initials :name="$user->name" class="h-8 w-8" />
                            <div class="min-w-0">
                                <span class="block truncate text-xs font-bold text-slate-800">{{ $user->name }}</span>
                                <span class="truncate text-[11px] font-medium text-amber-800">Mitra Kolektor</span>
                            </div>
                        </div>
                        <div class="flex shrink-0 items-center gap-1.5 rounded-full bg-emerald-100/80 px-2 py-0.5 text-[11px] font-bold text-emerald-800">
                            <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-emerald-600"></span>Aktif
                        </div>
                    </div>
                    <nav class="flex flex-col gap-1.5 text-sm font-medium">
                        <a href="{{ route('collector.dashboard') }}" class="sidebar-link {{ $current === 'collector.dashboard' ? 'sidebar-link-active-collector' : 'sidebar-link-inactive' }}">
                            <span class="material-symbols-outlined text-lg">dashboard</span> Ringkasan
                        </a>
                        <a href="{{ route('collector.pickup-requests.index') }}" class="sidebar-link {{ str_starts_with($current, 'collector.pickup-requests') ? 'sidebar-link-active-collector' : 'sidebar-link-inactive' }}">
                            <span class="material-symbols-outlined text-lg">local_shipping</span>
                            <span class="flex-1">Penjemputan aktif</span>
                            @if ($queueCount)
                                <span class="rounded-full {{ $current === 'collector.dashboard' || str_starts_with($current, 'collector.pickup-requests') ? 'bg-white/20 text-white' : 'bg-collector-100 text-collector-800' }} px-2 py-0.5 text-xs font-bold">{{ $queueCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('collector.history') }}" class="sidebar-link {{ $current === 'collector.history' ? 'sidebar-link-active-collector' : 'sidebar-link-inactive' }}">
                            <span class="material-symbols-outlined text-lg">receipt_long</span> Riwayat &amp; laporan
                        </a>
                    </nav>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-100 pt-3">
                    @csrf
                    <button type="submit" class="sidebar-link sidebar-link-inactive w-full text-slate-600 hover:text-red-600">
                        <span class="material-symbols-outlined">logout</span> Keluar
                    </button>
                </form>
            </div>
        </aside>

        <div id="overlay" class="fixed inset-0 z-30 hidden bg-slate-900/40 opacity-0 backdrop-blur-[2px] transition-opacity duration-200 lg:hidden"></div>

        <div class="min-w-0 flex-1">
            <header class="sticky top-0 z-20 hidden h-16 items-center justify-between border-b border-slate-200/80 bg-white/90 px-4 shadow-2xs backdrop-blur-md lg:flex lg:px-8">
                <div>
                    <h1 class="font-display text-lg font-bold tracking-tight text-slate-900">@yield('title', 'Kolektor')</h1>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2 rounded-full border border-amber-200/80 bg-amber-50/70 px-3.5 py-1.5 text-xs shadow-2xs whitespace-nowrap">
                        <span class="material-symbols-outlined text-base text-collector-600">payments</span>
                        <span class="font-semibold text-amber-800">Selesai dijemput:</span>
                        <span class="font-extrabold text-amber-900">{{ $user->assignedPickups()->where('status', 'collected')->count() }} tugas</span>
                    </div>
                    <x-user-initials :name="$user->name" class="h-8 w-8" />
                </div>
            </header>

            <main class="p-4 lg:p-8">
                @include('layouts.partials.flash')
                @yield('content')
            </main>
        </div>
    </div>

    <x-confirm-modal />

    @include('layouts.partials.sidebar-toggle')
    @stack('scripts')
</body>
</html>
