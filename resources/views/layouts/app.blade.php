<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TemJi')</title>
    @include('layouts.partials.head-assets')
</head>
<body class="bg-surface font-sans text-on-surface">
    @php
        $current = request()->route()?->getName();
        $user = auth()->user();
        $unread = $user->unreadNotificationsCount();
    @endphp

    <header class="sticky top-0 z-40 border-b border-outline-variant/40 bg-surface/90 shadow-sm backdrop-blur-md">
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between gap-3 px-4 sm:px-6 lg:px-8">
            <div class="flex min-w-0 items-center gap-3 sm:gap-6">
                <a href="{{ route('resident.dashboard') }}" class="flex shrink-0 items-center gap-2">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary text-on-primary shadow-sm">
                        <span class="material-symbols-outlined">recycling</span>
                    </span>
                    <span class="hidden sm:flex flex-col leading-none">
                        <span class="font-display text-lg font-black tracking-tight text-primary">TemJi</span>
                        <span class="text-[10px] font-semibold uppercase tracking-wider text-secondary">Eco Fin Warga</span>
                    </span>
                </a>
                <nav class="hidden items-center gap-6 md:flex">
                    <a href="{{ route('resident.dashboard') }}" class="top-nav-link {{ str_starts_with($current, 'resident.dashboard') ? 'top-nav-link-active' : '' }}">Ikhtisar</a>
                    <a href="{{ route('resident.pickup-requests.index') }}" class="top-nav-link {{ str_starts_with($current, 'resident.pickup-requests') ? 'top-nav-link-active' : '' }}">Jemput Sampah</a>
                    <a href="{{ route('resident.rewards.index') }}" class="top-nav-link {{ str_starts_with($current, 'resident.rewards') ? 'top-nav-link-active' : '' }}">Katalog Reward</a>
                    <a href="{{ route('resident.news.index') }}" class="top-nav-link {{ str_starts_with($current, 'resident.news') ? 'top-nav-link-active' : '' }}">Berita</a>
                </nav>
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
                <div class="hidden items-center gap-2 rounded-full border border-outline-variant/50 bg-surface-container-lowest px-3 py-1 shadow-sm sm:flex">
                    <span class="text-xs font-semibold text-tertiary">⭐ {{ number_format($user->points) }} Poin</span>
                    <span class="text-outline-variant">|</span>
                    <span class="text-xs font-semibold text-primary">{{ $user->cashBalanceLabel() }}</span>
                </div>
                <a href="{{ route('resident.rewards.index') }}" class="btn btn-primary btn-sm hidden xl:inline-flex">Tukar Poin</a>
                <a href="{{ route('resident.notifications.index') }}" class="relative rounded-full p-2 text-on-surface-variant hover:bg-surface-container" title="Notifikasi">
                    <span class="material-symbols-outlined">notifications</span>
                    @if ($unread)
                        <span class="absolute right-1.5 top-1.5 h-2 w-2 rounded-full bg-error"></span>
                    @endif
                </a>
                <div class="hidden items-center gap-2 pl-1 lg:flex">
                    <x-user-initials :name="$user->name" />
                    <div class="hidden flex-col text-left xl:flex">
                        <span class="text-xs font-semibold leading-tight">{{ $user->name }}</span>
                        <span class="text-[11px] font-medium text-secondary">Warga</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-sm">Keluar</button>
                </form>
                <button id="hamburger" class="inline-flex h-11 w-11 items-center justify-center rounded-lg text-on-surface md:hidden" type="button" aria-label="Menu">
                    <span class="material-symbols-outlined">menu</span>
                </button>
            </div>
        </div>
        <div id="mobile-nav" class="hidden border-t border-outline-variant/40 bg-surface-container-lowest px-4 py-4 md:hidden">
            <nav class="flex flex-col gap-3 text-sm font-semibold">
                <a href="{{ route('resident.dashboard') }}">Ikhtisar</a>
                <a href="{{ route('resident.pickup-requests.index') }}">Jemput Sampah</a>
                <a href="{{ route('resident.rewards.index') }}">Katalog Reward</a>
                <a href="{{ route('resident.point-histories.index') }}">Riwayat Poin</a>
                <a href="{{ route('resident.news.index') }}">Berita</a>
                <a href="{{ route('resident.notifications.index') }}">Notifikasi @if ($unread)({{ $unread }})@endif</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-error">Keluar</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="mx-auto w-full max-w-6xl px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
        @include('layouts.partials.flash')
        @yield('content')
    </main>

    <script>
        const hamburger = document.getElementById('hamburger');
        const mobileNav = document.getElementById('mobile-nav');
        hamburger?.addEventListener('click', () => mobileNav?.classList.toggle('hidden'));
    </script>
    @stack('scripts')
</body>
</html>
