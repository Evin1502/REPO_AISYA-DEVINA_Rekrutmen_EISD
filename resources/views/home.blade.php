@extends('layouts.landing')

@section('title', 'TemJi — Kurangi Tumpukan, Daur Ulang Lebih Banyak')

@section('content')

    {{-- ── HERO ── --}}
    <section class="mx-auto max-w-6xl px-5 py-16 sm:py-20">
        <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-2">
            <div>
                <span class="eyebrow"><span class="h-1.5 w-1.5 rounded-full bg-brand-500"></span> Gerakan kesadaran sampah</span>
                <h1 class="mt-4 text-4xl font-black leading-[1.05] tracking-tight sm:text-5xl">
                    Sampah tidak harus<br>menjadi <span class="text-brand-600">tumpukan</span>.
                </h1>
                <p class="mt-5 max-w-md text-base leading-relaxed text-ink/70">
                    TemJi membantu kamu memahami dampak sampah, memilah dengan benar, dan mengubahnya
                    menjadi <span class="font-semibold text-ink">poin dan reward nyata</span>.
                </p>
                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <a href="{{ route('register') }}" class="btn btn-primary px-6 py-3 text-base">Daftar &amp; mulai kurangi</a>
                    <a href="#dampak" class="text-sm font-semibold text-ink/70 hover:text-ink">Lihat dampaknya →</a>
                </div>
                <div class="mt-10 flex flex-wrap gap-x-8 gap-y-3 text-sm text-ink/60">
                    <span><b class="text-ink">{{ number_format($stats['residents']) }}</b> warga bergabung</span>
                    <span><b class="text-ink">{{ $wasteCategories->count() }}</b> kategori diterima</span>
                    <span><b class="text-ink">{{ number_format($stats['collectors']) }}</b> kolektor aktif</span>
                </div>
            </div>

            <div class="mx-auto w-full max-w-sm">
                <div class="receipt-card px-6 py-7">
                    <p class="text-center text-xs font-semibold uppercase tracking-widest text-ink/40">Contoh struk penjemputan</p>
                    <p class="mt-1 text-center text-sm text-ink/50">TemJi &middot; #TJ-0428</p>
                    <div class="mt-5 space-y-0.5">
                        @php
                            $demo = $wasteCategories->take(3)->values();
                            $demoWeights = [2.4, 1.5, 3.0];
                            $demoTotalWeight = 0;
                            $demoTotalPoints = 0;
                        @endphp
                        @forelse ($demo as $i => $category)
                            @php
                                $w = $demoWeights[$i] ?? 1.0;
                                $p = (int) round($w * $category->points_per_kg);
                                $demoTotalWeight += $w;
                                $demoTotalPoints += $p;
                            @endphp
                            <div class="receipt-row">
                                <span class="text-ink/70">{{ $category->name }} &middot; {{ number_format($w, 1) }} kg</span>
                                <span class="font-semibold text-ink">+{{ $p }} poin</span>
                            </div>
                        @empty
                            <div class="receipt-row"><span class="text-ink/50">Belum ada kategori sampah</span><span class="text-ink/50">—</span></div>
                        @endforelse
                    </div>
                    <div class="mt-3 flex items-baseline justify-between pt-2">
                        <span class="text-sm font-semibold text-ink">Total {{ number_format($demoTotalWeight, 1) }} kg</span>
                        <span class="text-xl font-black text-brand-600">{{ $demoTotalPoints }} poin</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── SDG ALIGNMENT ── --}}
    <section class="border-y border-black/5 bg-forest-800">
        <div class="mx-auto max-w-6xl px-5 py-10">
            <p class="text-center text-xs font-semibold uppercase tracking-widest text-white/50">
                Mendukung Sustainable Development Goals
            </p>
            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="flex items-start gap-4 rounded-xl bg-white/5 p-5 ring-1 ring-white/10">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-brand-500 text-lg font-black text-white">11</span>
                    <div>
                        <p class="font-semibold text-white">Sustainable Cities and Communities</p>
                        <p class="mt-1 text-sm leading-relaxed text-white/60">
                            Target 11.6 — mengurangi dampak lingkungan perkotaan lewat pengelolaan
                            sampah kota yang lebih terstruktur dan terlacak.
                        </p>
                    </div>
                </div>
                <div class="flex items-start gap-4 rounded-xl bg-white/5 p-5 ring-1 ring-white/10">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-gold text-lg font-black text-white">12</span>
                    <div>
                        <p class="font-semibold text-white">Responsible Consumption and Production</p>
                        <p class="mt-1 text-sm leading-relaxed text-white/60">
                            Target 12.5 — mendorong pemilahan &amp; daur ulang sampah lewat insentif
                            poin per kategori.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── CARA KERJA ── --}}
    <section id="cara-kerja" class="border-y border-black/5 bg-white">
        <div class="mx-auto max-w-6xl px-5 py-16">
            <h2 class="text-2xl font-bold tracking-tight sm:text-3xl">Cara kerjanya</h2>
            <div class="mt-10 grid grid-cols-1 gap-10 sm:grid-cols-3">
                <div class="border-t-2 border-clay pt-4">
                    <span class="text-sm font-semibold text-clay">01</span>
                    <p class="mt-2 font-semibold">Pilah &amp; daftarkan</p>
                    <p class="mt-1.5 text-sm leading-relaxed text-ink/60">Pisahkan sampah sesuai kategori, lalu ajukan penjemputan dari rumah.</p>
                </div>
                <div class="border-t-2 border-clay pt-4">
                    <span class="text-sm font-semibold text-clay">02</span>
                    <p class="mt-2 font-semibold">Kolektor menjemput</p>
                    <p class="mt-1.5 text-sm leading-relaxed text-ink/60">Kolektor datang, menimbang, dan mencocokkan tiap kategori di lokasi.</p>
                </div>
                <div class="border-t-2 border-clay pt-4">
                    <span class="text-sm font-semibold text-clay">03</span>
                    <p class="mt-2 font-semibold">Poin masuk otomatis</p>
                    <p class="mt-1.5 text-sm leading-relaxed text-ink/60">Setiap kilogram jadi poin di saldomu, siap ditukar reward.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ── KATEGORI ── --}}
    <section id="kategori" class="mx-auto max-w-6xl px-5 py-16">
        <div class="flex items-end justify-between gap-4">
            <h2 class="text-2xl font-bold tracking-tight sm:text-3xl">Kategori &amp; poin per kg</h2>
        </div>
        <p class="mt-2 max-w-lg text-sm text-ink/60">
            Semakin sulit didaur ulang, semakin tinggi nilainya — supaya sampah paling berdampak justru paling layak dikumpulkan.
        </p>
        <div class="mt-8 flex gap-4 overflow-x-auto pb-3">
            @forelse ($wasteCategories as $category)
                <div class="waste-tag min-w-[190px]">
                    <span class="text-sm font-semibold">{{ $category->name }}</span>
                    <span class="text-xs text-ink/50">{{ \Illuminate\Support\Str::limit($category->description, 42) }}</span>
                    <span class="mt-1 text-lg font-black text-clay">{{ $category->points_per_kg }} <span class="text-xs font-medium text-ink/50">poin/kg</span></span>
                </div>
            @empty
                <p class="text-sm text-ink/50">Kategori sampah belum ditambahkan admin.</p>
            @endforelse
        </div>
    </section>

    {{-- ── DAMPAK ── --}}
    <section id="dampak" class="bg-forest">
        <div class="mx-auto max-w-6xl px-5 py-16">
            <h2 class="text-2xl font-bold tracking-tight text-white sm:text-3xl">Dampak bersama, sejauh ini</h2>
            <p class="mt-2 max-w-lg text-sm text-white/60">Angka ini bertambah setiap kali penjemputan selesai dicatat oleh kolektor.</p>
            <div class="mt-10 grid grid-cols-2 gap-8 sm:grid-cols-4">
                <div><p class="stat-number">{{ number_format($stats['total_weight'], 1) }}</p><p class="stat-label">kg sampah terkumpul</p></div>
                <div><p class="stat-number">{{ number_format($stats['total_points']) }}</p><p class="stat-label">poin dibagikan</p></div>
                <div><p class="stat-number">{{ number_format($stats['residents']) }}</p><p class="stat-label">warga terdaftar</p></div>
                <div><p class="stat-number">{{ number_format($stats['collectors']) }}</p><p class="stat-label">kolektor aktif</p></div>
            </div>
        </div>
    </section>

    {{-- ── REWARD ── --}}
    <section id="reward" class="mx-auto max-w-6xl px-5 py-16">
        <h2 class="text-2xl font-bold tracking-tight sm:text-3xl">Tukar poin jadi reward</h2>
        <p class="mt-2 max-w-lg text-sm text-ink/60">Sebagian reward yang sedang tersedia untuk ditukar warga.</p>
        <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2">
            @forelse ($rewards as $reward)
                <div class="voucher-card">
                    <div class="voucher-stub">
                        <span class="-rotate-90 whitespace-nowrap text-xs font-bold tracking-wide text-gold">TEMJI</span>
                    </div>
                    <div class="flex-1 p-4">
                        <p class="font-semibold">{{ $reward->name }}</p>
                        <p class="mt-1 text-sm text-ink/60">{{ \Illuminate\Support\Str::limit($reward->description, 70) }}</p>
                        <div class="mt-3 flex items-center justify-between">
                            <span class="text-sm font-black text-gold">{{ number_format($reward->points_required) }} poin</span>
                            <span class="text-xs text-ink/40">Stok {{ $reward->stock }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-ink/50">Belum ada reward yang tersedia saat ini.</p>
            @endforelse
        </div>
    </section>

    {{-- ── CTA ── --}}
    <section class="border-t border-black/5 bg-white">
        <div class="mx-auto flex max-w-6xl flex-col items-start gap-6 px-5 py-16 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight sm:text-3xl">Mulai kelola sampahmu hari ini.</h2>
                <p class="mt-2 text-sm text-ink/60">Pendaftaran gratis, penjemputan pertama bisa diajukan langsung setelah masuk.</p>
            </div>
            <a href="{{ route('register') }}" class="btn btn-primary shrink-0 px-6 py-3 text-base">Daftar sebagai warga</a>
        </div>
    </section>

@endsection