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
        <div class="pointer-events-none absolute right-1/4 top-1/4 -z-10 h-96 w-96 rounded-full bg-emerald-200/30 blur-3xl"></div>
        <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-12">
            <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-12 lg:gap-10">
                <div class="lg:col-span-6">
                    <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-brand-200/80 bg-brand-50 px-3 py-1">
                        <span class="h-2 w-2 animate-pulse rounded-full bg-brand-600"></span>
                        <span class="text-[11px] font-extrabold uppercase tracking-widest text-brand-800">Gerakan kesadaran sampah</span>
                    </div>
                    <h1 class="mb-6 font-display text-4xl font-extrabold leading-[1.12] tracking-tight text-gray-950 sm:text-5xl lg:text-[58px]">
                        Sampah tidak harus menjadi <span class="text-brand-600 underline decoration-brand-200 decoration-wavy decoration-2">tumpukan.</span>
                    </h1>
                    <p class="mb-10 max-w-xl text-lg font-normal leading-relaxed text-gray-600 sm:text-xl">
                        TemJi membantu kamu memahami dampak sampah, memilah dengan benar, dan mengubahnya menjadi <strong class="font-bold text-gray-950">poin dan reward nyata</strong>.
                    </p>
                    <div class="mb-10 flex flex-wrap items-center gap-4 sm:gap-6">
                        <a class="inline-flex items-center gap-2 rounded-full bg-brand-600 px-8 py-4 text-base font-bold text-white shadow-lg shadow-brand-600/30 transition-all hover:-translate-y-0.5 hover:bg-brand-700" href="{{ route('register') }}">
                            Daftar &amp; mulai kurangi
                            <span class="material-symbols-outlined text-[19px]">trending_up</span>
                        </a>
                        <a class="group inline-flex items-center gap-3 rounded-full px-5 py-3 text-base font-semibold text-gray-800 transition-all hover:bg-white/80 hover:text-brand-700" href="#dampak">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 bg-white text-brand-600 shadow-sm transition-all group-hover:scale-105 group-hover:border-brand-500">
                                <span class="material-symbols-outlined text-lg">play_arrow</span>
                            </span>
                            Lihat dampaknya <span class="inline-block transition-transform group-hover:translate-x-1">→</span>
                        </a>
                    </div>
                    <div class="flex flex-wrap items-center gap-4 text-xs font-semibold text-gray-500">
                        <div class="flex -space-x-2 overflow-hidden">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-emerald-600 text-[11px] font-black text-white ring-2 ring-white">AR</span>
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-amber-500 text-[11px] font-black text-white ring-2 ring-white">SN</span>
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-teal-700 text-[11px] font-black text-white ring-2 ring-white">DW</span>
                        </div>
                        <span>Bergabung bersama <strong class="text-gray-900">{{ number_format($stats['residents']) }} warga</strong> yang sudah terdaftar</span>
                    </div>
                </div>

                <div class="relative flex justify-center lg:col-span-6 lg:justify-end">
                    <div class="relative w-full max-w-[510px]">
                        <div class="relative z-10 rounded-3xl border border-gray-100 bg-white p-6 shadow-dashboard sm:p-8">
                            <div class="flex items-start justify-between border-b border-gray-100 pb-6">
                                <div>
                                    <div class="mb-1 flex items-center gap-2">
                                        <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Total poin dibagikan</span>
                                        <span class="rounded-md border border-emerald-200/60 bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-700">Aktif</span>
                                    </div>
                                    <div class="flex items-baseline gap-2">
                                        <span class="font-display text-3xl font-black tracking-tight text-gray-950 sm:text-4xl">{{ number_format($stats['total_points']) }}</span>
                                        <span class="text-base font-bold text-gray-400">Poin</span>
                                    </div>
                                    <p class="mt-0.5 text-xs font-semibold text-brand-700">Dari {{ number_format($stats['total_weight'], 1) }} kg sampah terolah</p>
                                </div>
                            </div>
                            <div class="my-1 py-4">
                                <div class="mb-3 flex items-center justify-between text-xs font-bold text-gray-500">
                                    <span class="text-[11px] uppercase tracking-wider">Contoh nilai kategori</span>
                                    <span class="rounded-full border border-brand-200/60 bg-brand-50 px-2 py-0.5 text-[11px] font-semibold text-brand-700">Poin / kg</span>
                                </div>
                                <div class="space-y-2.5">
                                    @forelse ($wasteCategories->take(3) as $i => $category)
                                        <div class="flex items-center justify-between rounded-xl border border-gray-100 bg-[#f8faf7] p-2.5">
                                            <div class="flex items-center gap-2.5">
                                                <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg {{ $chipBg[$i % count($chipBg)] }} text-xs">{{ $iconFor($category->name) }}</div>
                                                <div>
                                                    <p class="text-xs font-bold leading-tight text-gray-900">{{ $category->name }}</p>
                                                    <p class="text-[11px] text-gray-500">{{ \Illuminate\Support\Str::limit($category->description, 36) }}</p>
                                                </div>
                                            </div>
                                            <span class="font-mono text-xs font-extrabold text-brand-700">+{{ $category->points_per_kg }}</span>
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-500">Kategori sampah belum ditambahkan.</p>
                                    @endforelse
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3 pt-3">
                                <div class="rounded-2xl border border-gray-100 bg-[#f7f9f6] p-3.5">
                                    <div class="mb-1 flex items-center gap-1.5 text-xs font-medium text-gray-500">
                                        <span class="material-symbols-outlined text-[16px] text-emerald-600">recycling</span>
                                        Total didaur ulang
                                    </div>
                                    <p class="text-lg font-black text-gray-900">{{ number_format($stats['total_weight'], 1) }} <span class="text-xs font-bold text-gray-500">kg</span></p>
                                </div>
                                <div class="rounded-2xl border border-gray-100 bg-[#f7f9f6] p-3.5">
                                    <div class="mb-1 flex items-center gap-1.5 text-xs font-medium text-gray-500">
                                        <span class="material-symbols-outlined text-[16px] text-teal-600">groups</span>
                                        Kolektor aktif
                                    </div>
                                    <p class="text-lg font-black text-gray-900">{{ number_format($stats['collectors']) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="absolute -bottom-7 -left-2 z-20 flex max-w-[320px] items-center gap-3.5 rounded-2xl border border-emerald-700/50 bg-forest p-4 text-white shadow-floating sm:-left-8">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/15 text-emerald-300">
                                <span class="material-symbols-outlined text-2xl">check_circle</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-1.5">
                                    <p class="text-xs font-bold tracking-tight text-white">Penjemputan terverifikasi</p>
                                </div>
                                <p class="mt-0.5 text-[11px] text-emerald-200/80">Struk digital + poin otomatis setelah ditimbang</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="border-y border-gray-200/70 bg-white py-8" id="dampak">
        <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-12">
            <div class="grid grid-cols-1 items-center gap-8 lg:grid-cols-12">
                <div class="grid grid-cols-3 gap-4 border-b border-gray-200/80 pb-6 sm:gap-6 lg:col-span-7 lg:border-b-0 lg:border-r lg:pb-0 lg:pr-8">
                    <div>
                        <p class="font-display text-2xl font-black tracking-tight text-gray-950 sm:text-3xl">{{ number_format($stats['residents']) }}</p>
                        <p class="mt-1 text-xs font-semibold text-gray-500 sm:text-sm">Warga terdaftar</p>
                    </div>
                    <div>
                        <p class="font-display text-2xl font-black tracking-tight text-brand-600 sm:text-3xl">{{ number_format($stats['total_weight'] / 1000, 2) }} <span class="text-lg">ton</span></p>
                        <p class="mt-1 text-xs font-semibold text-gray-500 sm:text-sm">Sampah terolah</p>
                    </div>
                    <div>
                        <p class="font-display text-2xl font-black tracking-tight text-gray-950 sm:text-3xl">{{ number_format($stats['collectors']) }}</p>
                        <p class="mt-1 text-xs font-semibold text-gray-500 sm:text-sm">Kolektor aktif</p>
                    </div>
                </div>
                <div class="lg:col-span-5" id="mitra">
                    <span class="mb-3 block text-[11px] font-black uppercase tracking-widest text-gray-400">Mendukung agenda keberlanjutan global</span>
                    <div class="flex flex-wrap items-center gap-2 text-xs font-bold text-gray-700 sm:gap-3">
                        <span class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 bg-gray-50 px-3 py-1.5"><span class="h-2 w-2 rounded-full bg-amber-500"></span> SDG 11 Kota</span>
                        <span class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 bg-gray-50 px-3 py-1.5"><span class="h-2 w-2 rounded-full bg-orange-500"></span> SDG 12 Konsumsi</span>
                        <span class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 bg-gray-50 px-3 py-1.5"><span class="h-2 w-2 rounded-full bg-emerald-600"></span> SDG 13 Aksi iklim</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-[#f8faf7] py-16 sm:py-24" id="cara-kerja">
        <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-12">
            <div class="mb-12 max-w-2xl sm:mb-16">
                <div class="mb-3 inline-flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-brand-600"></span>
                    <span class="text-xs font-black uppercase tracking-widest text-brand-700">Langkah sederhana</span>
                </div>
                <h2 class="font-display text-3xl font-black tracking-tight text-gray-950 sm:text-4xl">Bagaimana TemJi mengubah sampahmu jadi berkah</h2>
                <p class="mt-3 text-base text-gray-600">Tiga langkah terstruktur dari rumah tangga hingga penukaran insentif nyata.</p>
            </div>
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <div class="group rounded-3xl border border-gray-100 bg-white p-8 transition-all duration-300 hover:border-brand-200 hover:shadow-lg">
                    <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-50 text-xl font-black text-brand-700 shadow-sm transition-all group-hover:bg-brand-600 group-hover:text-white">1</div>
                    <h3 class="mb-3 text-xl font-bold text-gray-950">Pilah dari rumah</h3>
                    <p class="text-sm leading-relaxed text-gray-600">Pisahkan sampah anorganik berdasarkan material seperti plastik, kertas, kaleng logam, dan kaca bersih.</p>
                </div>
                <div class="group rounded-3xl border border-gray-100 bg-white p-8 transition-all duration-300 hover:border-brand-200 hover:shadow-lg">
                    <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-50 text-xl font-black text-brand-700 shadow-sm transition-all group-hover:bg-brand-600 group-hover:text-white">2</div>
                    <h3 class="mb-3 text-xl font-bold text-gray-950">Jemput atau setor</h3>
                    <p class="text-sm leading-relaxed text-gray-600">Kolektor terverifikasi TemJi mengambil ke rumah sesuai jadwal, atau kamu setor mandiri ke drop-point.</p>
                </div>
                <div class="group rounded-3xl border border-gray-100 bg-white p-8 transition-all duration-300 hover:border-brand-200 hover:shadow-lg" id="reward">
                    <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-50 text-xl font-black text-brand-700 shadow-sm transition-all group-hover:bg-brand-600 group-hover:text-white">3</div>
                    <h3 class="mb-3 text-xl font-bold text-gray-950">Dapatkan reward</h3>
                    <p class="text-sm leading-relaxed text-gray-600">Dapatkan struk digital berpoin. Tukarkan dengan saldo e-wallet, pulsa, atau barang dari katalog.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="border-t border-gray-200/70 bg-white py-16 sm:py-20" id="kategori">
        <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-12">
            <div class="mb-10 flex flex-col justify-between gap-4 md:mb-12 md:flex-row md:items-end">
                <div>
                    <div class="mb-2 inline-flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-brand-600"></span>
                        <span class="text-xs font-black uppercase tracking-widest text-brand-700">Daftar material</span>
                    </div>
                    <h2 class="font-display text-3xl font-black tracking-tight text-gray-950">Kategori sampah diterima</h2>
                </div>
                <p class="text-sm font-medium text-gray-500">Nilai poin dihitung per kilogram dan disesuaikan dengan kondisi pemilahan</p>
            </div>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 sm:gap-5 lg:grid-cols-5">
                @forelse ($wasteCategories as $i => $category)
                    <div class="group rounded-2xl border border-gray-100 bg-[#f8faf7] p-5 transition-all hover:border-brand-300 hover:bg-white hover:shadow-md sm:p-6">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl {{ $chipBg[$i % count($chipBg)] }} text-2xl transition-transform group-hover:scale-110">{{ $iconFor($category->name) }}</div>
                        <h4 class="mb-1 text-base font-bold text-gray-900">{{ $category->name }}</h4>
                        <span class="inline-block rounded-full border border-brand-200/60 bg-brand-50 px-2.5 py-1 text-xs font-bold text-brand-800">{{ $category->points_per_kg }} poin/kg</span>
                    </div>
                @empty
                    <p class="col-span-full text-sm text-gray-500">Kategori sampah belum ditambahkan admin.</p>
                @endforelse
            </div>
        </div>
    </section>

    @if ($rewards->isNotEmpty())
        <section class="bg-[#f8faf7] py-16">
            <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-12">
                <h2 class="font-display text-3xl font-black tracking-tight text-gray-950">Tukar poin jadi reward</h2>
                <p class="mt-2 max-w-lg text-sm text-gray-500">Sebagian reward yang sedang tersedia untuk ditukar warga.</p>
                <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    @foreach ($rewards as $reward)
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
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="relative overflow-hidden bg-forest py-20 text-white sm:py-24">
        <div class="pointer-events-none absolute -left-32 -top-32 h-96 w-96 rounded-full bg-brand-500/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-32 -right-32 h-96 w-96 rounded-full bg-emerald-400/15 blur-3xl"></div>
        <div class="relative z-10 mx-auto max-w-4xl px-5 text-center sm:px-6">
            <span class="mb-6 inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-bold tracking-wide text-emerald-200">
                <span class="h-2 w-2 animate-pulse rounded-full bg-emerald-400"></span>
                Ayo beraksi hari ini
            </span>
            <h2 class="mb-6 font-display text-3xl font-extrabold leading-tight tracking-tight sm:text-5xl">Siap kurangi timbulan sampah di lingkunganmu?</h2>
            <p class="mx-auto mb-10 max-w-2xl text-lg font-medium leading-relaxed text-emerald-100/90 sm:text-xl">
                Daftar sekarang, pilah dari rumah tangga, pantau tabungan lingkunganmu, dan nikmati reward langsung setiap minggunya.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4">
                <a class="inline-flex items-center gap-2 rounded-full bg-brand-500 px-8 py-4 font-bold text-white shadow-xl shadow-brand-950/30 transition-all hover:-translate-y-0.5 hover:bg-brand-600" href="{{ route('register') }}">
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
@endsection
