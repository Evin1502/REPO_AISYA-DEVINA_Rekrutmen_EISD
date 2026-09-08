@extends('layouts.landing')

@section('title', 'TemJi — Kurangi Tumpukan, Ubah Sampah Jadi Berkah')

@section('content')
    @php
        $iconFor = function ($name) {
            $n = strtolower($name);
            return match (true) {
                str_contains($n, 'logam') || str_contains($n, 'kaleng') || str_contains($n, 'alumunium') || str_contains($n, 'aluminium') => '🥫',
                str_contains($n, 'plastik') => '🧴',
                str_contains($n, 'kertas') || str_contains($n, 'kardus') => '📦',
                str_contains($n, 'elektronik') || str_contains($n, 'e-waste') => '⚡',
                str_contains($n, 'kaca') || str_contains($n, 'beling') => '🍾',
                str_contains($n, 'minyak') || str_contains($n, 'jelantah') => '🛢️',
                default => '♻️',
            };
        };
        $chipBg = ['bg-amber-100/70', 'bg-blue-100/70', 'bg-orange-100/70', 'bg-yellow-100/70', 'bg-emerald-100/70'];
    @endphp

    <section class="relative overflow-hidden pb-16 pt-10 lg:pb-24 lg:pt-16">
        <div class="pointer-events-none absolute right-1/4 top-1/4 -z-10 h-96 w-96 rounded-full bg-emerald-300/25 blur-3xl animate-pulse-glow"></div>
        <div class="pointer-events-none absolute left-10 top-1/3 -z-10 h-72 w-72 rounded-full bg-brand-200/20 blur-3xl animate-pulse-glow" style="animation-delay: 2s;"></div>
        <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-12">
            <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-12 lg:gap-10">
                <div class="lg:col-span-6">
                    <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-brand-200 bg-brand-50 px-3.5 py-1 animate-float-subtle">
                        <span class="h-2 w-2 animate-pulse rounded-full bg-brand-600"></span>
                        <span class="text-[11px] font-extrabold uppercase tracking-widest text-brand-800">Gerakan kesadaran sampah</span>
                    </div>
                    <h1 class="mb-6 font-display text-4xl font-extrabold leading-[1.12] tracking-tight text-slate-900 sm:text-5xl lg:text-[58px]">
                        Sampah tidak harus menjadi <span class="text-brand-600 underline decoration-brand-200 decoration-wavy decoration-2">tumpukan.</span>
                    </h1>
                    <p class="mb-10 max-w-xl text-lg font-normal leading-relaxed text-slate-600 sm:text-xl">
                        TemJi membantu kamu memahami dampak sampah, memilah dengan benar, dan mengubahnya menjadi <strong class="font-bold text-slate-900">poin dan reward nyata</strong>.
                    </p>
                    <div class="mb-10 flex flex-wrap items-center gap-4 sm:gap-6">
                        <a class="inline-flex items-center gap-2 rounded-full bg-brand-600 px-8 py-4 text-base font-bold text-white shadow-lg shadow-brand-600/30 transition-all hover:-translate-y-1 hover:bg-brand-700 hover:shadow-brand-600/40" href="{{ route('register') }}">
                            Daftar &amp; mulai kurangi
                            <span class="material-symbols-outlined text-[19px]">trending_up</span>
                        </a>
                        <a class="group inline-flex items-center gap-3 rounded-full px-5 py-3 text-base font-semibold text-slate-700 transition-all hover:bg-white hover:text-brand-600 hover:shadow-sm" href="#dampak">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-brand-600 shadow-xs transition-all group-hover:scale-110 group-hover:border-brand-500">
                                <span class="material-symbols-outlined text-lg">play_arrow</span>
                            </span>
                            Lihat dampaknya <span class="inline-block transition-transform group-hover:translate-x-1">→</span>
                        </a>
                    </div>
                    <div class="flex flex-wrap items-center gap-4 text-xs font-semibold text-slate-600">
                        <div class="flex -space-x-2 overflow-hidden">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-emerald-600 text-[11px] font-black text-white ring-2 ring-white transition-transform hover:z-10 hover:scale-110">AR</span>
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-amber-500 text-[11px] font-black text-white ring-2 ring-white transition-transform hover:z-10 hover:scale-110">SN</span>
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-teal-700 text-[11px] font-black text-white ring-2 ring-white transition-transform hover:z-10 hover:scale-110">DW</span>
                        </div>
                        <span>Bergabung bersama <strong class="text-slate-900">{{ number_format($stats['residents']) }} warga</strong> yang sudah terdaftar</span>
                    </div>
                </div>

                <div class="relative flex justify-center lg:col-span-6 lg:justify-end">
                    <div class="relative w-full max-w-[510px] animate-float-slow">
                        <div class="relative z-10 rounded-3xl border border-slate-200/80 bg-white p-6 shadow-dashboard transition-all duration-300 hover:shadow-2xl sm:p-8">
                            <div class="flex items-start justify-between border-b border-slate-100 pb-6">
                                <div>
                                    <div class="mb-1 flex items-center gap-2">
                                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total poin dibagikan</span>
                                        <span class="rounded-md border border-emerald-200 bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-700">Aktif</span>
                                    </div>
                                    <div class="flex items-baseline gap-2">
                                        <span class="font-display text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">{{ number_format($stats['total_points']) }}</span>
                                        <span class="text-base font-bold text-slate-500">Poin</span>
                                    </div>
                                    <p class="mt-0.5 text-xs font-semibold text-brand-700">Dari {{ number_format($stats['total_weight'], 1) }} kg sampah terolah</p>
                                </div>
                            </div>
                            <div class="my-1 py-4">
                                <div class="mb-3 flex items-center justify-between text-xs font-bold text-slate-600">
                                    <span class="text-[11px] uppercase tracking-wider text-slate-400">Contoh nilai kategori</span>
                                    <span class="rounded-full border border-brand-200 bg-brand-50 px-2.5 py-0.5 text-[11px] font-semibold text-brand-700">Poin / kg</span>
                                </div>
                                <div class="space-y-2.5">
                                    @forelse ($wasteCategories->take(3) as $i => $category)
                                        <div class="flex items-center justify-between rounded-2xl border border-slate-200/70 bg-slate-50/80 p-3 transition-transform hover:-translate-y-0.5">
                                            <div class="flex items-center gap-3">
                                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl {{ $chipBg[$i % count($chipBg)] }} text-sm shadow-2xs">{{ $iconFor($category->name) }}</div>
                                                <div>
                                                    <p class="text-xs font-bold leading-tight text-slate-900">{{ $category->name }}</p>
                                                    <p class="text-[11px] text-slate-500">{{ \Illuminate\Support\Str::limit($category->description, 36) }}</p>
                                                </div>
                                            </div>
                                            <span class="font-mono text-xs font-extrabold text-brand-700 bg-brand-50 px-2 py-1 rounded-lg border border-brand-100">+{{ $category->points_per_kg }}</span>
                                        </div>
                                    @empty
                                        <p class="text-sm text-slate-500">Kategori sampah belum ditambahkan.</p>
                                    @endforelse
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3 pt-3">
                                <div class="rounded-2xl border border-slate-200/70 bg-slate-50/80 p-3.5 transition-transform hover:-translate-y-0.5">
                                    <div class="mb-1 flex items-center gap-1.5 text-xs font-medium text-slate-600">
                                        <span class="material-symbols-outlined text-[16px] text-emerald-600">recycling</span>
                                        Total didaur ulang
                                    </div>
                                    <p class="text-lg font-black text-slate-900">{{ number_format($stats['total_weight'], 1) }} <span class="text-xs font-bold text-slate-500">kg</span></p>
                                </div>
                                <div class="rounded-2xl border border-slate-200/70 bg-slate-50/80 p-3.5 transition-transform hover:-translate-y-0.5">
                                    <div class="mb-1 flex items-center gap-1.5 text-xs font-medium text-slate-600">
                                        <span class="material-symbols-outlined text-[16px] text-amber-600">groups</span>
                                        Kolektor aktif
                                    </div>
                                    <p class="text-lg font-black text-slate-900">{{ number_format($stats['collectors']) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="absolute -bottom-7 -left-2 z-20 flex max-w-[320px] items-center gap-3.5 rounded-2xl border border-emerald-700/50 bg-slate-900 p-4 text-white shadow-floating sm:-left-8 animate-float-delayed">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-brand-600 text-white shadow-sm">
                                <span class="material-symbols-outlined text-2xl">check_circle</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-1.5">
                                    <p class="text-xs font-bold tracking-tight text-white">Penjemputan terverifikasi</p>
                                </div>
                                <p class="mt-0.5 text-[11px] text-slate-300">Struk digital + poin otomatis setelah ditimbang</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="border-y border-slate-200/80 bg-white py-12" id="dampak">
        <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-12">
            <div class="grid grid-cols-1 items-center gap-8 lg:grid-cols-12">
                <div class="grid grid-cols-3 gap-4 border-b border-slate-100 pb-6 sm:gap-6 lg:col-span-6 lg:border-b-0 lg:border-r lg:pb-0 lg:pr-8">
                    <div>
                        <p class="font-display text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">{{ number_format($stats['residents']) }}</p>
                        <p class="mt-1 text-xs font-semibold text-slate-500 sm:text-sm">Warga terdaftar</p>
                    </div>
                    <div>
                        <p class="font-display text-2xl font-black tracking-tight text-brand-600 sm:text-3xl">{{ number_format($stats['total_weight'] / 1000, 2) }} <span class="text-lg">ton</span></p>
                        <p class="mt-1 text-xs font-semibold text-slate-500 sm:text-sm">Sampah terolah</p>
                    </div>
                    <div>
                        <p class="font-display text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">{{ number_format($stats['collectors']) }}</p>
                        <p class="mt-1 text-xs font-semibold text-slate-500 sm:text-sm">Kolektor aktif</p>
                    </div>
                </div>
                <div class="lg:col-span-6" id="mitra">
                    <span class="mb-3 block text-[11px] font-black uppercase tracking-widest text-slate-400">Mendukung agenda keberlanjutan global (SDGs)</span>
                    <div class="flex flex-wrap items-center gap-2 text-xs font-bold text-slate-800 sm:gap-3">
                        <span class="inline-flex items-center gap-1.5 rounded-xl border border-amber-200 bg-amber-50 px-3.5 py-2 text-amber-900 shadow-2xs" title="SDG 11: Kota dan Permukiman yang Berkelanjutan"><span class="h-2 w-2 rounded-full bg-amber-500"></span> SDG 11 Kota &amp; Permukiman Berkelanjutan</span>
                        <span class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200/80 bg-slate-50 px-3.5 py-2 shadow-2xs" title="SDG 12: Konsumsi dan Produksi yang Bertanggung Jawab"><span class="h-2 w-2 rounded-full bg-orange-500"></span> SDG 12 Konsumsi &amp; Produksi</span>
                        <span class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200/80 bg-slate-50 px-3.5 py-2 shadow-2xs" title="SDG 13: Penanganan Perubahan Iklim"><span class="h-2 w-2 rounded-full bg-emerald-600"></span> SDG 13 Aksi Iklim</span>
                    </div>
                </div>
            </div>

            <!-- Kartu Edukasi SDG 11: Aksi Nyata & Pengelolaan Sampah -->
            <div class="mt-10 overflow-hidden rounded-3xl border border-amber-200/80 bg-gradient-to-br from-amber-50/90 via-white to-emerald-50/50 p-6 shadow-sm sm:p-8 lg:p-10">
                <div class="grid grid-cols-1 items-center gap-8 lg:grid-cols-12">
                    <div class="lg:col-span-8">
                        <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-amber-200/80 bg-amber-100/70 px-3.5 py-1 text-amber-900">
                            <span class="material-symbols-outlined text-sm text-amber-700">location_city</span>
                            <span class="text-[11px] font-extrabold uppercase tracking-widest">Aksi Nyata &amp; Pengelolaan Sampah</span>
                        </div>
                        <h3 class="mb-3 font-display text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                            SDG 11: Kota dan Permukiman yang Berkelanjutan
                        </h3>
                        <p class="text-sm leading-relaxed text-slate-700 sm:text-base">
                            Platform ini dirancang untuk menjawab tantangan urbanisasi dengan berfokus pada <strong>SDG 11: Kota dan Permukiman yang Berkelanjutan</strong>. Melalui sistem pengelolaan sampah terpadu yang melibatkan partisipasi aktif warga dan kolektor lokal, kami berkomitmen menciptakan lingkungan permukiman yang lebih bersih, sehat, dan tangguh. Setiap pilahan sampah yang terolah secara terukur tidak hanya mengurangi beban tempat pembuangan akhir (TPA), tetapi juga menjadi langkah nyata dalam membangun komunitas yang peduli terhadap keberlanjutan kota.
                        </p>
                    </div>
                    <div class="flex flex-col gap-3 lg:col-span-4">
                        <div class="rounded-2xl border border-white/80 bg-white/90 p-4 shadow-2xs backdrop-blur-xs">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-500 text-white shadow-sm">
                                    <span class="material-symbols-outlined">delete_sweep</span>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900">Pengurangan Beban TPA</h4>
                                    <p class="text-[11px] text-slate-500">Mencegah penumpukan limbah anorganik</p>
                                </div>
                            </div>
                        </div>
                        <div class="rounded-2xl border border-white/80 bg-white/90 p-4 shadow-2xs backdrop-blur-xs">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-600 text-white shadow-sm">
                                    <span class="material-symbols-outlined">groups</span>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900">Kolaborasi Warga &amp; Kolektor</h4>
                                    <p class="text-[11px] text-slate-500">Partisipasi aktif ekosistem lokal</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-slate-50 py-16 sm:py-24" id="cara-kerja">
        <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-12">
            <div class="mb-12 max-w-2xl sm:mb-16">
                <div class="mb-3 inline-flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-brand-600"></span>
                    <span class="text-xs font-black uppercase tracking-widest text-brand-700">Langkah sederhana</span>
                </div>
                <h2 class="font-display text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Bagaimana TemJi mengubah sampahmu jadi berkah</h2>
                <p class="mt-3 text-base text-slate-600">Tiga langkah terstruktur dari rumah tangga hingga penukaran insentif nyata.</p>
            </div>
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <div class="group rounded-3xl border border-slate-200/80 bg-white p-8 transition-all duration-300 hover:border-brand-300 hover:shadow-lg">
                    <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-50 text-xl font-black text-brand-700 shadow-2xs transition-all group-hover:bg-brand-600 group-hover:text-white">1</div>
                    <h3 class="mb-3 text-xl font-bold text-slate-900">Pilah dari rumah</h3>
                    <p class="text-sm leading-relaxed text-slate-600">Pisahkan sampah anorganik berdasarkan material seperti plastik, kertas, kaleng logam, dan kaca bersih.</p>
                </div>
                <div class="group rounded-3xl border border-slate-200/80 bg-white p-8 transition-all duration-300 hover:border-brand-300 hover:shadow-lg">
                    <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-50 text-xl font-black text-brand-700 shadow-2xs transition-all group-hover:bg-brand-600 group-hover:text-white">2</div>
                    <h3 class="mb-3 text-xl font-bold text-slate-900">Jemput atau setor</h3>
                    <p class="text-sm leading-relaxed text-slate-600">Kolektor terverifikasi TemJi mengambil ke rumah sesuai jadwal, atau kamu setor mandiri ke drop-point.</p>
                </div>
                <div class="group rounded-3xl border border-slate-200/80 bg-white p-8 transition-all duration-300 hover:border-brand-300 hover:shadow-lg" id="reward">
                    <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-50 text-xl font-black text-brand-700 shadow-2xs transition-all group-hover:bg-brand-600 group-hover:text-white">3</div>
                    <h3 class="mb-3 text-xl font-bold text-slate-900">Dapatkan reward</h3>
                    <p class="text-sm leading-relaxed text-slate-600">Dapatkan struk digital berpoin. Tukarkan dengan saldo e-wallet, pulsa, atau barang dari katalog.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="border-t border-slate-200/80 bg-white py-16 sm:py-20" id="kategori">
        <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-12">
            <div class="mb-10 flex flex-col justify-between gap-4 md:mb-12 md:flex-row md:items-end">
                <div>
                    <div class="mb-2 inline-flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-brand-600"></span>
                        <span class="text-xs font-black uppercase tracking-widest text-brand-700">Daftar material</span>
                    </div>
                    <h2 class="font-display text-3xl font-black tracking-tight text-slate-900">Kategori sampah diterima</h2>
                </div>
                <p class="text-sm font-medium text-slate-500">Nilai poin dihitung per kilogram dan disesuaikan dengan kondisi pemilahan</p>
            </div>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 sm:gap-5 lg:grid-cols-5">
                @forelse ($wasteCategories as $i => $category)
                    <div class="group rounded-2xl border border-slate-200/80 bg-slate-50/70 p-5 transition-all hover:border-brand-300 hover:bg-white hover:shadow-md sm:p-6">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl {{ $chipBg[$i % count($chipBg)] }} text-2xl shadow-2xs transition-transform group-hover:scale-110">{{ $iconFor($category->name) }}</div>
                        <h4 class="mb-1 text-base font-bold text-slate-900">{{ $category->name }}</h4>
                        <span class="inline-block rounded-full border border-brand-200 bg-brand-50 px-2.5 py-1 text-xs font-bold text-brand-800">{{ $category->points_per_kg }} poin/kg</span>
                    </div>
                @empty
                    <p class="col-span-full text-sm text-slate-500">Kategori sampah belum ditambahkan admin.</p>
                @endforelse
            </div>
        </div>
    </section>

    @if ($rewards->isNotEmpty())
        <section class="bg-slate-50 py-16">
            <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-12">
                <h2 class="font-display text-3xl font-black tracking-tight text-slate-900">Tukar poin jadi reward</h2>
                <p class="mt-2 max-w-lg text-sm text-slate-600">Sebagian reward yang sedang tersedia untuk ditukar warga.</p>
                <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    @foreach ($rewards as $reward)
                        <div class="voucher-card">
                            <div class="voucher-stub">
                                <span class="-rotate-90 whitespace-nowrap text-xs font-bold tracking-wide text-amber-700">TEMJI</span>
                            </div>
                            <div class="flex-1 p-4">
                                <p class="font-bold text-slate-900">{{ $reward->name }}</p>
                                <p class="mt-1 text-sm text-slate-500">{{ \Illuminate\Support\Str::limit($reward->description, 70) }}</p>
                                <div class="mt-3 flex items-center justify-between">
                                    <span class="text-sm font-black text-amber-700">⭐ {{ number_format($reward->points_required) }} poin</span>
                                    <span class="text-xs text-slate-400">Stok {{ $reward->stock }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="relative overflow-hidden bg-slate-900 py-20 text-white sm:py-24">
        <div class="pointer-events-none absolute -left-32 -top-32 h-96 w-96 rounded-full bg-brand-500/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-32 -right-32 h-96 w-96 rounded-full bg-emerald-400/15 blur-3xl"></div>
        <div class="relative z-10 mx-auto max-w-4xl px-5 text-center sm:px-6">
            <span class="mb-6 inline-flex items-center gap-2 rounded-full border border-emerald-500/30 bg-emerald-500/10 px-3.5 py-1 text-xs font-bold tracking-wide text-emerald-300">
                <span class="h-2 w-2 animate-pulse rounded-full bg-emerald-400"></span>
                Ayo beraksi hari ini
            </span>
            <h2 class="mb-6 font-display text-3xl font-extrabold leading-tight tracking-tight sm:text-5xl">Siap kurangi timbulan sampah di lingkunganmu?</h2>
            <p class="mx-auto mb-10 max-w-2xl text-lg font-medium leading-relaxed text-slate-300 sm:text-xl">
                Daftar sekarang, pilah dari rumah tangga, pantau tabungan lingkunganmu, dan nikmati reward langsung setiap minggunya.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4">
                <a class="inline-flex items-center gap-2 rounded-full bg-brand-600 px-8 py-4 font-bold text-white shadow-xl shadow-brand-900/40 transition-all hover:-translate-y-0.5 hover:bg-brand-700" href="{{ route('register') }}">
                    Daftar sebagai warga
                    <span class="material-symbols-outlined text-[19px]">person_add</span>
                </a>
                <a class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-8 py-4 font-semibold text-white backdrop-blur transition-all hover:bg-white/15" href="{{ route('login') }}">
                    Masuk kolektor
                    <span class="material-symbols-outlined text-[19px]">local_shipping</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Scroll-Triggered 3D Pop-Out Column CTA Widget -->
    <div id="scroll-popup" class="fixed bottom-5 left-4 right-4 z-50 transform scale-90 translate-y-12 opacity-0 pointer-events-none transition-all duration-500 ease-out sm:left-auto sm:right-6 sm:w-96">
        <div class="relative rounded-3xl border border-brand-300 bg-white/95 p-5 shadow-[0_25px_60px_-15px_rgba(22,163,74,0.3)] backdrop-blur-xl ring-2 ring-brand-500/20">
            <!-- Overlapping Pop-Out Icon Badge -->
            <div class="absolute -top-4 -left-2 flex h-11 w-11 items-center justify-center rounded-2xl bg-brand-600 text-white shadow-lg ring-4 ring-white shadow-brand-600/30">
                <span class="material-symbols-outlined text-2xl animate-pulse">recycling</span>
            </div>

            <!-- Close Button -->
            <button id="close-scroll-popup" class="absolute right-3 top-3 flex h-7 w-7 items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition-colors" type="button" aria-label="Tutup">
                <span class="material-symbols-outlined text-base">close</span>
            </button>

            <div class="pt-2 pl-9">
                <div class="mb-1 flex items-center gap-1.5">
                    <span class="text-[11px] font-extrabold uppercase tracking-widest text-brand-700">TemJi Eco Fin</span>
                    <span class="h-1.5 w-1.5 rounded-full bg-brand-600 animate-ping"></span>
                </div>
                <h4 class="text-base font-black text-slate-900 leading-snug">Sampah Rumah Tangga Bisa Jadi Saldo! 💰</h4>
                <p class="mt-1 text-xs text-slate-600 leading-relaxed">Pilah dari rumah, disetorkan/dijemput kolektor, langsung cair poin &amp; saldo.</p>
            </div>

            <div class="mt-3 flex items-center gap-2 rounded-xl bg-brand-50/80 border border-brand-100 px-3 py-2 text-xs font-bold text-brand-800">
                <span class="material-symbols-outlined text-sm text-brand-600">stars</span>
                <span>{{ number_format($stats['residents']) }} Warga Sudah Bergabung</span>
            </div>

            <div class="mt-4 flex items-center gap-2.5">
                <a href="{{ route('register') }}" class="btn btn-primary flex-1 text-xs py-2.5 shadow-md shadow-brand-600/25">
                    <span class="material-symbols-outlined text-sm">rocket_launch</span>
                    Daftar &amp; Mulai Poin
                </a>
                <a href="{{ route('login') }}" class="btn btn-secondary text-xs py-2.5 px-3">
                    Masuk
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const popup = document.getElementById('scroll-popup');
            const closeBtn = document.getElementById('close-scroll-popup');
            let isDismissed = false;

            if (!popup) return;

            const handleScroll = () => {
                if (isDismissed) return;
                if (window.scrollY > 350) {
                    popup.classList.remove('scale-90', 'translate-y-12', 'opacity-0', 'pointer-events-none');
                    popup.classList.add('scale-100', 'translate-y-0', 'opacity-100', 'pointer-events-auto');
                } else {
                    popup.classList.add('scale-90', 'translate-y-12', 'opacity-0', 'pointer-events-none');
                    popup.classList.remove('scale-100', 'translate-y-0', 'opacity-100', 'pointer-events-auto');
                }
            };

            window.addEventListener('scroll', handleScroll, { passive: true });
            handleScroll();

            closeBtn?.addEventListener('click', () => {
                isDismissed = true;
                popup.classList.add('scale-90', 'translate-y-12', 'opacity-0', 'pointer-events-none');
                popup.classList.remove('scale-100', 'translate-y-0', 'opacity-100', 'pointer-events-auto');
            });
        });
    </script>
    @endpush
@endsection
