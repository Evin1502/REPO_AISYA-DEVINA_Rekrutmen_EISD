<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TemJi — Kurangi Tumpukan, Ubah Sampah Jadi Berkah')</title>
    <meta name="description" content="TemJi adalah bank sampah digital: pelajari dampak sampah, pilah dengan benar, jadwalkan penjemputan, lalu tukarkan sampahmu jadi poin dan reward.">
    @include('layouts.partials.head-assets')
</head>
<body class="bg-slate-50 font-sans text-slate-900 antialiased selection:bg-brand-600 selection:text-white">
    <header class="sticky top-0 z-50 border-b border-slate-200/70 bg-white/85 backdrop-blur-md">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-5 sm:h-20 sm:px-8 lg:px-12">
            <a href="{{ route('home') }}" class="group flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-600 text-xl font-extrabold text-white shadow-md shadow-brand-600/25 transition-transform group-hover:scale-105">T</span>
                <span class="flex items-center gap-2">
                    <span class="font-display text-2xl font-black tracking-tight text-slate-900">Tem<span class="text-brand-600">Ji</span></span>
                    <span class="hidden rounded-full border border-brand-200 bg-brand-50 px-2.5 py-0.5 text-[11px] font-bold text-brand-700 sm:inline-flex">Eco Fin</span>
                </span>
            </a>

            <nav class="hidden items-center gap-8 lg:gap-10 md:flex">
                <a class="relative text-[15px] font-semibold text-slate-600 transition-colors after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-0 after:bg-brand-600 after:transition-all after:duration-200 hover:text-brand-600 hover:after:w-full" href="#cara-kerja">Cara kerja</a>
                <a class="relative text-[15px] font-semibold text-slate-600 transition-colors after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-0 after:bg-brand-600 after:transition-all after:duration-200 hover:text-brand-600 hover:after:w-full" href="#kategori">Kategori</a>
                <a class="relative text-[15px] font-semibold text-slate-600 transition-colors after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-0 after:bg-brand-600 after:transition-all after:duration-200 hover:text-brand-600 hover:after:w-full" href="#dampak">Dampak</a>
                <a class="relative text-[15px] font-semibold text-slate-600 transition-colors after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-0 after:bg-brand-600 after:transition-all after:duration-200 hover:text-brand-600 hover:after:w-full" href="#reward">Reward</a>
                <a class="relative text-[15px] font-semibold text-slate-600 transition-colors after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-0 after:bg-brand-600 after:transition-all after:duration-200 hover:text-brand-600 hover:after:w-full" href="#mitra">Mitra</a>
            </nav>

            <div class="flex items-center gap-3 sm:gap-4">
                <a class="hidden text-[15px] font-semibold text-slate-700 transition-colors hover:text-brand-600 sm:inline" href="{{ route('login') }}">Masuk</a>
                <a class="btn btn-primary btn-pill px-5 py-2.5 text-sm sm:px-7" href="{{ route('register') }}">
                    Daftar &amp; Mulai
                    <span class="material-symbols-outlined text-[17px]">arrow_forward</span>
                </a>
                <button id="hamburger" class="inline-flex h-11 w-11 items-center justify-center rounded-xl text-slate-700 hover:bg-slate-100 md:hidden" type="button" aria-label="Menu">
                    <span class="material-symbols-outlined">menu</span>
                </button>
            </div>
        </div>
        <div id="mobile-nav" class="hidden animate-fade-in-up border-t border-slate-200/80 bg-white px-5 py-4 shadow-lg md:hidden">
            <nav class="flex flex-col gap-3 text-sm font-semibold text-slate-800">
                <a href="#cara-kerja">Cara kerja</a>
                <a href="#kategori">Kategori</a>
                <a href="#dampak">Dampak</a>
                <a href="#reward">Reward</a>
                <a href="#mitra">Mitra</a>
                <a href="{{ route('login') }}" class="pt-2 text-brand-700">Masuk</a>
            </nav>
        </div>
    </header>

    <div class="mx-auto max-w-7xl px-5 pt-4 sm:px-8">
        @include('layouts.partials.flash')
    </div>

    <main>
        @yield('content')
    </main>

    <footer class="border-t border-slate-200/80 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-6 px-5 py-10 sm:flex-row sm:items-center sm:justify-between sm:px-8 lg:px-12">
            <div>
                <div class="flex items-center gap-2 font-display text-lg font-black text-slate-900">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-600 text-sm text-white">T</span>
                    Tem<span class="text-brand-600">Ji</span>
                </div>
                <p class="mt-2 max-w-sm text-sm text-slate-500">&copy; {{ date('Y') }} TemJi — Gerakan Kesadaran Sampah Berkelanjutan</p>
            </div>
            <div class="flex flex-wrap gap-6 text-sm font-medium text-slate-600">
                <a class="hover:text-brand-600" href="#cara-kerja">Cara kerja</a>
                <a class="hover:text-brand-600" href="#kategori">Kategori</a>
                <a class="hover:text-brand-600" href="{{ route('login') }}">Masuk</a>
                <a class="hover:text-brand-600" href="{{ route('register') }}">Daftar</a>
            </div>
        </div>
    </footer>

    <script>
        const hamburger = document.getElementById('hamburger');
        const mobileNav = document.getElementById('mobile-nav');
        hamburger?.addEventListener('click', () => mobileNav?.classList.toggle('hidden'));
        mobileNav?.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', () => mobileNav.classList.add('hidden'));
        });
    </script>
    @stack('scripts')
</body>
</html>
