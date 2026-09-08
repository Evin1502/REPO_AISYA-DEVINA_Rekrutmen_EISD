@extends('layouts.app')

@section('title', 'Ikhtisar - TemJi')

@section('content')
    <section class="flex flex-col gap-6">
        <div class="flex flex-col justify-between gap-4 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm md:flex-row md:items-center md:p-6">
            <div>
                <div class="mb-2 inline-flex items-center gap-2 rounded-full border border-brand-200 bg-brand-50 px-3 py-0.5 text-[11px] font-bold text-brand-800">
                    <span class="h-1.5 w-1.5 rounded-full bg-brand-600"></span>
                    Program pilah hijau
                </div>
                <h1 class="font-display text-2xl font-bold tracking-tight text-slate-900">Halo, {{ auth()->user()->name }}! 👋</h1>
                <p class="mt-1 text-sm text-slate-500">Kelola sampah rumah tanggamu &amp; tukarkan poin dengan saldo dompet digital atau barang.</p>
            </div>
            <div class="flex items-center gap-2 self-start rounded-xl border border-slate-200/80 bg-slate-50 px-4 py-2.5 text-xs text-slate-600 md:self-auto">
                <span class="material-symbols-outlined text-base text-slate-400">history</span>
                @if ($lastCollected)
                    Terakhir setor: <strong class="text-slate-800">{{ $lastCollected->updated_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</strong>
                @else
                    Belum ada setoran selesai
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 lg:grid-cols-12">
            <div class="flex flex-col justify-between rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm lg:col-span-8 lg:p-6">
                <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Saldo tabungan sampah</span>
                        <div class="mt-1 flex flex-wrap items-baseline gap-2">
                            <span class="font-display text-4xl font-black tracking-tight text-slate-900 sm:text-5xl">{{ number_format($pointsBalance) }}</span>
                            <span class="text-lg font-bold text-brand-700">Poin</span>
                        </div>
                        <p class="mt-2 flex items-center gap-1.5 text-sm text-slate-500">
                            <span class="material-symbols-outlined text-slate-400">account_balance_wallet</span>
                            Setara dengan <strong class="text-slate-900">{{ $cashBalanceLabel }} saldo dompet</strong>
                        </p>
                    </div>
                    <div class="flex items-center gap-2.5 self-start rounded-xl border border-amber-200/80 bg-amber-50/70 px-4 py-2.5">
                        <span class="material-symbols-outlined text-amber-600">loyalty</span>
                        <div>
                            <div class="text-[11px] font-medium text-amber-800">Notifikasi baru</div>
                            <div class="text-sm font-bold text-amber-900">{{ $unreadNotifications }} pesan</div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3 border-t border-slate-100 pt-4">
                    <a href="{{ route('resident.rewards.index') }}" class="btn btn-primary">
                        <span class="material-symbols-outlined text-base">currency_exchange</span>
                        Tukar ke e-wallet / barang
                    </a>
                    <a href="{{ route('resident.point-histories.index') }}" class="btn btn-secondary">
                        <span class="material-symbols-outlined text-base">receipt_long</span>
                        Riwayat struk
                    </a>
                    <a href="{{ route('resident.pickup-requests.index') }}" class="ml-auto inline-flex items-center gap-1 text-sm font-bold text-brand-700 hover:underline">
                        {{ $pendingPickups }} pengajuan menunggu
                        <span class="material-symbols-outlined text-base">chevron_right</span>
                    </a>
                </div>
            </div>

            <div class="relative flex flex-col justify-between rounded-2xl bg-brand-700 p-5 text-white shadow-sm lg:col-span-4 lg:p-6">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center gap-1 rounded-full bg-white/15 px-3 py-1 text-[11px] font-bold text-emerald-100 backdrop-blur-xs">
                            <span class="material-symbols-outlined text-sm">flash_on</span> Cepat &amp; terpilah
                        </span>
                        <span class="material-symbols-outlined text-emerald-200">local_shipping</span>
                    </div>
                    <h2 class="mt-4 font-display text-xl font-bold text-white">Penjemputan baru</h2>
                    <p class="mt-1 text-sm text-emerald-100/90">Kolektor siap menimbang sampah langsung di depan rumah Anda.</p>
                    <div class="mt-5 rounded-xl border border-white/15 bg-black/15 p-3.5 backdrop-blur-xs">
                        <div class="flex items-center gap-1.5 text-[11px] font-bold text-emerald-200">
                            <span class="material-symbols-outlined text-sm">recycling</span>
                            Total sudah terkumpul
                        </div>
                        <div class="mt-1 text-lg font-black text-white">{{ number_format($collectedWeight, 1) }} kg</div>
                    </div>
                </div>
                <a href="{{ route('resident.pickup-requests.create') }}" class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-white py-3 text-sm font-bold text-brand-800 shadow-sm transition hover:bg-brand-50">
                    <span class="material-symbols-outlined">add_circle</span>
                    Jadwalkan jemput sampah
                </a>
            </div>
        </div>
    </section>

    @if ($activePickup)
        <section class="mt-6 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm lg:p-6">
            <div class="flex flex-col justify-between gap-3 border-b border-slate-100 pb-4 md:flex-row md:items-center">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 text-brand-700 border border-brand-100">
                        <span class="material-symbols-outlined">local_shipping</span>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-900">Penjemputan aktif #TJ-{{ str_pad($activePickup->id, 4, '0', STR_PAD_LEFT) }}</p>
                        <p class="text-xs text-slate-500">{{ $activePickup->address }}</p>
                    </div>
                </div>
                <a href="{{ route('resident.pickup-requests.show', $activePickup) }}" class="text-sm font-bold text-brand-700 hover:underline">Lihat detail</a>
            </div>
            <div class="mt-5 overflow-x-auto">
                <x-pickup-status-stepper :status="$activePickup->status" />
            </div>
            <div class="mt-4 flex flex-wrap gap-1.5">
                @foreach ($activePickup->wasteCategories as $category)
                    <span class="rounded-lg border border-brand-200 bg-brand-50 px-2.5 py-1 text-xs font-semibold text-brand-800">{{ $category->name }}</span>
                @endforeach
            </div>
        </section>
    @endif

    <div class="mt-6 grid grid-cols-1 gap-5 lg:grid-cols-12">
        <div class="lg:col-span-7">
            <div class="mb-3 flex items-center justify-between">
                <h2 class="font-display text-lg font-bold text-slate-900">Aktivitas poin terbaru</h2>
                <a href="{{ route('resident.point-histories.index') }}" class="text-sm font-bold text-brand-700 hover:underline">Lihat semua</a>
            </div>
            @if ($recentHistories->isEmpty())
                <x-empty-state message="Belum ada aktivitas poin. Ajukan penjemputan sampah untuk mulai mengumpulkan poin!"
                               :action-route="route('resident.pickup-requests.create')" action-label="Ajukan Penjemputan" />
            @else
                <div class="card divide-y divide-slate-100">
                    @foreach ($recentHistories as $history)
                        <div class="flex items-center justify-between px-5 py-3.5">
                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ $history->description }}</p>
                                <p class="text-xs text-slate-400">{{ $history->created_at->format('d M Y H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="{{ $history->points > 0 ? 'text-brand-700' : 'text-red-600' }} text-sm font-extrabold">
                                    {{ $history->points > 0 ? '+' : '' }}{{ number_format($history->points) }}
                                </p>
                                <x-status-badge :status="$history->type" class="mt-1" />
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="flex flex-col gap-5 lg:col-span-5">
            <div class="kpi-card">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Dampak ekologis</p>
                <p class="mt-2 font-display text-3xl font-black text-slate-900">{{ number_format($collectedWeight, 1) }} <span class="text-base font-bold text-slate-500">kg</span></p>
                <p class="mt-1 text-xs text-slate-500">Sampah rumah tangga yang sudah diverifikasi kolektor.</p>
            </div>
            @if ($featuredReward)
                <div class="voucher-card">
                    <div class="voucher-stub">
                        <span class="-rotate-90 whitespace-nowrap text-xs font-bold tracking-wide text-amber-700">REWARD</span>
                    </div>
                    <div class="flex-1 p-4">
                        <p class="text-[11px] font-extrabold uppercase tracking-wide text-slate-400">Rekomendasi</p>
                        <p class="mt-1 font-bold text-slate-900">{{ $featuredReward->name }}</p>
                        <p class="mt-2 text-sm font-black text-amber-700">⭐ {{ number_format($featuredReward->points_required) }} poin</p>
                        <a href="{{ route('resident.rewards.index') }}" class="mt-3 inline-block text-sm font-bold text-brand-700 hover:underline">Tukarkan</a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if ($recentPickups->isNotEmpty())
        <div class="mt-6">
            <div class="mb-3 flex items-center justify-between">
                <h2 class="font-display text-lg font-bold text-slate-900">Riwayat penjemputan</h2>
                <a href="{{ route('resident.pickup-requests.index') }}" class="text-sm font-bold text-brand-700 hover:underline">Semua pengajuan</a>
            </div>
            <div class="card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th>ID</th>
                                <th>Kategori</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentPickups as $pickup)
                                <tr>
                                    <td class="font-mono font-bold text-brand-700">#TJ-{{ str_pad($pickup->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td>
                                        <div class="flex flex-wrap gap-1">
                                            @foreach ($pickup->wasteCategories as $category)
                                                <span class="rounded-full border border-brand-200 bg-brand-50 px-2 py-0.5 text-xs font-semibold text-brand-800">{{ $category->name }}</span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="text-slate-600">{{ $pickup->created_at->format('d M Y') }}</td>
                                    <td><x-status-badge :status="$pickup->status" :label="$pickup->statusLabel()" /></td>
                                    <td class="text-right">
                                        <a href="{{ route('resident.pickup-requests.show', $pickup) }}" class="btn btn-secondary btn-sm">Struk</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection
