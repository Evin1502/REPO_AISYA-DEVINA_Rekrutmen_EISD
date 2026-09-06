<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TemJi — Kurangi Tumpukan, Daur Ulang Lebih Banyak')</title>
    <meta name="description" content="TemJi adalah bank sampah digital: pelajari dampak sampah, pilah dengan benar, jadwalkan penjemputan, lalu tukarkan sampahmu jadi poin dan reward.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-paper font-sans text-ink antialiased">

    {{-- ── Navbar ── --}}
    <header class="sticky top-0 z-30 border-b border-black/5 bg-paper/90 backdrop-blur">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-5 py-4">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-lg font-black tracking-tight">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-600 text-sm text-white">T</span>
                Tem<span class="text-brand-600">Ji</span>
            </a>

            <nav class="hidden items-center gap-7 text-sm font-medium text-ink/70 md:flex">
                <a href="#cara-kerja" class="hover:text-ink">Cara kerja</a>
                <a href="#kategori" class="hover:text-ink">Kategori</a>
                <a href="#dampak" class="hover:text-ink">Dampak</a>
                <a href="#reward" class="hover:text-ink">Reward</a>
            </nav>

            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="hidden text-sm font-semibold text-ink/70 hover:text-ink sm:inline">Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm sm:px-5 sm:py-2.5 sm:text-sm">Daftar</a>
            </div>
        </div>
    </header>

    @if (session('success'))
        <div class="mx-auto max-w-6xl px-5 pt-4"><x-alert type="success">{{ session('success') }}</x-alert></div>
    @endif
    @if (session('error'))
        <div class="mx-auto max-w-6xl px-5 pt-4"><x-alert type="error">{{ session('error') }}</x-alert></div>
    @endif

    <main>
        @yield('content')
    </main>

    {{-- ── Footer ── --}}
    <footer class="border-t border-black/5 bg-white">
        <div class="mx-auto max-w-6xl px-5 py-12">
            <div class="grid grid-cols-1 gap-10 sm:grid-cols-3">
                <div>
                    <div class="flex items-center gap-2 text-lg font-black">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-600 text-sm text-white">T</span>
                        Tem<span class="text-brand-600">Ji</span>
                    </div>
                    <p class="mt-3 max-w-xs text-sm text-ink/60">
                        Menuju nol sampah di pembuangan akhir: memahami, memilah, dan menghargai setiap kilogram yang bisa kembali bernilai.
                    </p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-ink">Untuk warga</p>
                    <ul class="mt-3 space-y-2 text-sm text-ink/60">
                        <li><a href="{{ route('register') }}" class="hover:text-ink">Daftar sebagai warga</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-ink">Masuk ke akun</a></li>
                        <li><a href="#kategori" class="hover:text-ink">Kategori sampah &amp; poin</a></li>
                        <li><a href="#reward" class="hover:text-ink">Katalog reward</a></li>
                    </ul>
                </div>
                <div>
                    <p class="text-sm font-semibold text-ink">Tentang program</p>
                    <ul class="mt-3 space-y-2 text-sm text-ink/60">
                        <li><a href="#cara-kerja" class="hover:text-ink">Cara kerja TemJi</a></li>
                        <li><a href="#dampak" class="hover:text-ink">Dampak bersama</a></li>
                    </ul>
                </div>
            </div>
            <p class="mt-10 border-t border-black/5 pt-6 text-xs text-ink/40">
                &copy; {{ date('Y') }} TemJi. Dibangun untuk mendukung pengelolaan sampah yang bertanggung jawab.
            </p>
        </div>
    </footer>

</body>
</html>