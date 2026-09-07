<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - TemJi')</title>
    @include('layouts.partials.head-assets')
</head>
<body class="bg-surface font-sans text-on-surface">
    @php
        $current = request()->route()?->getName();
        $user = auth()->user();
        $pendingPickups = \App\Models\PickupRequest::where('status', 'pending')->count();
        $pendingExchanges = \App\Models\PointExchange::where('status', 'pending')->count();
    @endphp

    <div class="min-h-screen lg:flex">
        <div class="sticky top-0 z-30 flex items-center justify-between border-b border-outline-variant/40 bg-surface-container-low px-4 py-3 lg:hidden">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 font-display font-black text-primary">
                <span class="material-symbols-outlined">recycling</span> TemJi
            </a>
            <button id="hamburger" class="inline-flex h-11 w-11 items-center justify-center rounded-lg" type="button" aria-label="Menu">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </div>

        <aside id="sidebar" class="shell-sidebar">
            <div class="flex h-full flex-col justify-between">
                <div class="flex flex-col gap-4">
                    <div class="flex items-center gap-2 px-1">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary text-on-primary shadow-sm">
                            <span class="material-symbols-outlined">recycling</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="font-display text-base font-bold tracking-tight text-primary">TemJi</span>
                                <span class="rounded bg-secondary-container px-1.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-on-secondary-container">Admin</span>
                            </div>
                            <p class="text-[11px] font-medium text-on-surface-variant">Hub pengelola</p>
                        </div>
                    </div>
                    <nav class="mt-1 flex flex-col gap-1">
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ $current === 'admin.dashboard' ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                            <span class="material-symbols-outlined text-[20px]">dashboard</span> Ringkasan
                        </a>
                        <a href="{{ route('admin.pickup-requests.index') }}" class="sidebar-link {{ str_starts_with($current, 'admin.pickup-requests') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                            <span class="material-symbols-outlined text-[20px]">local_shipping</span>
                            <span class="flex-1">Penjemputan</span>
                            @if ($pendingPickups)
                                <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[11px] font-bold text-amber-800">{{ $pendingPickups }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.waste-categories.index') }}" class="sidebar-link {{ str_starts_with($current, 'admin.waste-categories') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                            <span class="material-symbols-outlined text-[20px]">inventory_2</span> Kategori material
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ str_starts_with($current, 'admin.users') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                            <span class="material-symbols-outlined text-[20px]">group</span> Data warga &amp; mitra
                        </a>
                        <a href="{{ route('admin.rewards.index') }}" class="sidebar-link {{ str_starts_with($current, 'admin.rewards') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                            <span class="material-symbols-outlined text-[20px]">redeem</span> Katalog reward
                        </a>
                        <a href="{{ route('admin.point-exchanges.index') }}" class="sidebar-link {{ str_starts_with($current, 'admin.point-exchanges') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                            <span class="material-symbols-outlined text-[20px]">account_balance_wallet</span>
                            <span class="flex-1">Rekonsiliasi poin</span>
                            @if ($pendingExchanges)
                                <span class="rounded-full bg-primary/10 px-2 py-0.5 text-[11px] font-bold text-primary">{{ $pendingExchanges }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.news.index') }}" class="sidebar-link {{ str_starts_with($current, 'admin.news') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                            <span class="material-symbols-outlined text-[20px]">campaign</span> Berita
                        </a>
                    </nav>
                </div>
                <div class="border-t border-outline-variant/40 pt-3">
                    <div class="mb-3 flex items-center justify-between rounded-md bg-surface-container px-3 py-1.5 text-[11px]">
                        <div class="flex items-center gap-1.5">
                            <span class="h-2 w-2 animate-pulse rounded-full bg-primary"></span>
                            <span class="font-medium text-on-surface-variant">Sistem aktif</span>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="sidebar-link sidebar-link-inactive w-full">
                            <span class="material-symbols-outlined text-[20px]">logout</span> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div id="overlay" class="fixed inset-0 z-30 hidden bg-forest/45 backdrop-blur-[2px] lg:hidden"></div>

        <div class="min-w-0 flex-1">
            <header class="sticky top-0 z-20 hidden h-16 items-center justify-between border-b border-outline-variant/40 bg-surface-container-lowest/90 px-4 shadow-sm backdrop-blur-md lg:flex lg:px-8">
                <div>
                    <h1 class="font-display text-lg font-bold">@yield('title', 'Admin')</h1>
                </div>
                <div class="flex items-center gap-3">
                    <x-user-initials :name="$user->name" />
                    <div class="text-left">
                        <p class="text-xs font-semibold leading-tight">{{ $user->name }}</p>
                        <p class="text-[11px] text-on-surface-variant">Admin operasional</p>
                    </div>
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
