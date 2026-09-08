<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TemJi')</title>
    @include('layouts.partials.head-assets')
</head>
<body class="bg-surface font-sans text-slate-900">
    @php
        $current = request()->route()?->getName();
        $user = auth()->user();
        $unread = $user->unreadNotificationsCount();
    @endphp

    <header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/95 shadow-sm backdrop-blur-md">
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
            <div class="flex min-w-0 items-center gap-4 sm:gap-6 lg:gap-8">
                <a href="{{ route('resident.dashboard') }}" class="flex shrink-0 items-center gap-2.5">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-600 text-white shadow-sm shadow-brand-600/20">
                        <span class="material-symbols-outlined">recycling</span>
                    </span>
                    <span class="hidden flex-col leading-none sm:flex">
                        <span class="font-display text-lg font-black tracking-tight text-slate-900">TemJi</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-brand-700">Eco Fin Warga</span>
                    </span>
                </a>
                <nav class="hidden shrink-0 items-center gap-4 text-xs md:flex lg:gap-6 lg:text-sm">
                    <a href="{{ route('resident.dashboard') }}" class="top-nav-link {{ str_starts_with($current, 'resident.dashboard') ? 'top-nav-link-active' : '' }}">Beranda</a>
                    <a href="{{ route('resident.pickup-requests.index') }}" class="top-nav-link {{ str_starts_with($current, 'resident.pickup-requests') ? 'top-nav-link-active' : '' }}">Jemput Sampah</a>
                    <a href="{{ route('resident.rewards.index') }}" class="top-nav-link {{ str_starts_with($current, 'resident.rewards') ? 'top-nav-link-active' : '' }}">Katalog Reward</a>
                    <a href="{{ route('resident.news.index') }}" class="top-nav-link {{ str_starts_with($current, 'resident.news') ? 'top-nav-link-active' : '' }}">Berita</a>
                </nav>
            </div>

            <div class="flex shrink-0 items-center gap-2.5 pl-2 sm:gap-3">
                <a href="{{ route('resident.rewards.index') }}"
                   class="hidden items-center gap-2 rounded-full border border-amber-200/90 bg-amber-50/90 px-3.5 py-1.5 shadow-2xs transition-all hover:bg-amber-100/90 hover:border-amber-300 whitespace-nowrap sm:flex"
                   title="Klik untuk tukar poin & lihat reward">
                    <span class="text-xs font-bold text-amber-800 whitespace-nowrap">⭐ {{ number_format($user->points) }} Poin</span>
                    <span class="text-amber-300">|</span>
                    <span class="text-xs font-extrabold text-brand-800 whitespace-nowrap">{{ $user->cashBalanceLabel() }}</span>
                </a>
                <a href="{{ route('resident.notifications.index') }}" class="relative rounded-full p-2 text-slate-600 transition-colors hover:bg-slate-100 hover:text-slate-900" title="Notifikasi">
                    <span class="material-symbols-outlined">notifications</span>
                    @if ($unread)
                        <span class="absolute right-1.5 top-1.5 h-2 w-2 rounded-full bg-red-600"></span>
                    @endif
                </a>
                <div class="hidden items-center gap-2.5 pl-1 lg:flex">
                    <x-user-initials :name="$user->name" />
                    <div class="hidden flex-col text-left xl:flex">
                        <span class="text-xs font-bold leading-tight text-slate-800">{{ $user->name }}</span>
                        <span class="text-[11px] font-semibold text-brand-700">Warga</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-sm">Keluar</button>
                </form>
                <button id="hamburger" class="inline-flex h-10 w-10 items-center justify-center rounded-xl text-slate-700 hover:bg-slate-100 md:hidden" type="button" aria-label="Menu">
                    <span class="material-symbols-outlined">menu</span>
                </button>
            </div>
        </div>
        <div id="mobile-nav" class="hidden animate-fade-in-up border-t border-slate-200/80 bg-white px-4 py-4 shadow-lg md:hidden">
            <nav class="flex flex-col gap-3 text-sm font-semibold text-slate-800">
                <a href="{{ route('resident.dashboard') }}" class="hover:text-brand-600">Beranda</a>
                <a href="{{ route('resident.pickup-requests.index') }}" class="hover:text-brand-600">Jemput Sampah</a>
                <a href="{{ route('resident.rewards.index') }}" class="hover:text-brand-600">Katalog Reward</a>
                <a href="{{ route('resident.point-histories.index') }}" class="hover:text-brand-600">Riwayat Poin</a>
                <a href="{{ route('resident.news.index') }}" class="hover:text-brand-600">Berita</a>
                <a href="{{ route('resident.notifications.index') }}" class="hover:text-brand-600">Notifikasi @if ($unread)({{ $unread }})@endif</a>
                <form method="POST" action="{{ route('logout') }}" class="pt-1 border-t border-slate-100">
                    @csrf
                    <button type="submit" class="text-red-600 hover:underline">Keluar</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="mx-auto w-full max-w-6xl px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
        @include('layouts.partials.flash')
        @yield('content')
    </main>

    <x-confirm-modal />

    <script>
        const hamburger = document.getElementById('hamburger');
        const mobileNav = document.getElementById('mobile-nav');
        hamburger?.addEventListener('click', () => mobileNav?.classList.toggle('hidden'));
    </script>
    @stack('scripts')
</body>
</html>
