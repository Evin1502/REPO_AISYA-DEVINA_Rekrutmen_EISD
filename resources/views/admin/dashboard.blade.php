@extends('layouts.admin')

@section('title', 'Ringkasan operasional')

@section('content')
    <section class="flex flex-col gap-4">
        <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
            <div>
                <h1 class="font-display text-xl font-bold tracking-tight">Kinerja sirkularitas &amp; operasional</h1>
                <p class="text-sm text-on-surface-variant">Metrik penjemputan, timbangan, dan distribusi reward warga.</p>
            </div>
            <div class="inline-flex items-center gap-1.5 self-start rounded-full bg-primary/10 px-3 py-1.5 text-xs font-medium text-primary sm:self-auto">
                <span class="material-symbols-outlined text-base">verified</span>
                Data live dari penjemputan terverifikasi
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="kpi-card">
                <div class="mb-2 flex items-center justify-between text-on-surface-variant">
                    <span class="text-xs font-semibold uppercase tracking-wide">Total sampah terolah</span>
                    <span class="material-symbols-outlined text-primary">recycling</span>
                </div>
                <div class="my-1 flex items-baseline gap-1">
                    <span class="font-display text-4xl font-black">{{ number_format($stats['totalWeight'] / 1000, 2) }}</span>
                    <span class="text-sm font-semibold text-on-surface-variant">ton</span>
                </div>
                <div class="mt-3 flex items-center justify-between border-t border-outline-variant/30 pt-2 text-[11px] text-on-surface-variant">
                    <span>{{ number_format($stats['totalWeight'], 1) }} kg</span>
                    <span>{{ number_format($stats['todayCollected']) }} selesai hari ini</span>
                </div>
            </div>
            <div class="kpi-card">
                <div class="mb-2 flex items-center justify-between text-on-surface-variant">
                    <span class="text-xs font-semibold uppercase tracking-wide">Warga terdaftar</span>
                    <span class="material-symbols-outlined text-secondary">people</span>
                </div>
                <div class="my-1 flex items-baseline gap-1">
                    <span class="font-display text-4xl font-black">{{ number_format($stats['totalResidents']) }}</span>
                    <span class="text-sm font-semibold text-on-surface-variant">KK</span>
                </div>
                <div class="mt-3 border-t border-outline-variant/30 pt-2 text-[11px] text-on-surface-variant">Akun role resident</div>
            </div>
            <div class="kpi-card">
                <div class="mb-2 flex items-center justify-between text-on-surface-variant">
                    <span class="text-xs font-semibold uppercase tracking-wide">Armada mitra</span>
                    <span class="material-symbols-outlined text-secondary">electric_rickshaw</span>
                </div>
                <div class="my-1 flex items-baseline gap-1">
                    <span class="font-display text-4xl font-black">{{ number_format($stats['totalCollectors']) }}</span>
                    <span class="text-sm font-semibold text-on-surface-variant">kolektor</span>
                </div>
                <div class="mt-3 border-t border-outline-variant/30 pt-2 text-[11px] text-emerald-700 font-semibold">{{ number_format($stats['pendingPickups']) }} menunggu persetujuan</div>
            </div>
            <div class="kpi-card">
                <div class="mb-2 flex items-center justify-between text-on-surface-variant">
                    <span class="text-xs font-semibold uppercase tracking-wide">Reward disalurkan</span>
                    <span class="material-symbols-outlined text-amber-600">payments</span>
                </div>
                <div class="my-1 flex items-baseline gap-1">
                    <span class="text-base font-bold text-on-surface-variant">Rp</span>
                    <span class="font-display text-3xl font-black sm:text-4xl">{{ number_format($stats['approvedSaldoValue'], 0, ',', '.') }}</span>
                </div>
                <div class="mt-3 flex items-center justify-between border-t border-outline-variant/30 pt-2 text-[11px]">
                    <span class="font-semibold text-amber-800">⭐ {{ number_format($stats['totalPointsDistributed']) }} pts</span>
                    <span class="text-on-surface-variant">{{ number_format($stats['pendingExchanges']) }} menunggu</span>
                </div>
            </div>
        </div>
    </section>

    <div class="mt-8 grid grid-cols-1 gap-6 xl:grid-cols-3">
        <div class="xl:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h2 class="font-display text-lg font-bold">Antrean penjemputan terkini</h2>
                    <p class="text-xs text-on-surface-variant">Mutasi pengajuan warga dan status alur operasional.</p>
                </div>
                <a href="{{ route('admin.pickup-requests.index') }}" class="text-sm font-semibold text-primary hover:underline">Lihat semua</a>
            </div>
            <div class="card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead class="bg-surface-container-low/80">
                            <tr>
                                <th>ID</th>
                                <th>Warga</th>
                                <th>Kolektor</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentPickupRequests as $pickupRequest)
                                <tr class="hover:bg-surface-container-low/40">
                                    <td>
                                        <span class="block font-mono font-bold text-primary">TJ-{{ str_pad($pickupRequest->id, 4, '0', STR_PAD_LEFT) }}</span>
                                        <span class="text-[11px] text-outline">{{ $pickupRequest->created_at->format('H:i') }} WIB</span>
                                    </td>
                                    <td class="font-medium">{{ $pickupRequest->resident?->name ?? '-' }}</td>
                                    <td>{{ $pickupRequest->collector?->name ?? '—' }}</td>
                                    <td>{{ $pickupRequest->created_at->format('d M Y') }}</td>
                                    <td><x-status-badge :status="$pickupRequest->status" /></td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.pickup-requests.show', $pickupRequest) }}" class="btn btn-primary btn-sm">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-sm text-on-surface-variant">Belum ada pengajuan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div>
            <h2 class="mb-4 font-display text-lg font-bold">Aksi cepat</h2>
            <div class="space-y-3">
                <a href="{{ route('admin.pickup-requests.index', ['status' => 'pending']) }}" class="card flex items-center justify-between p-4 hover:ring-primary/30">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-50 text-primary"><span class="material-symbols-outlined">local_shipping</span></span>
                        <span class="text-sm font-semibold">Setujui pengajuan</span>
                    </div>
                    <span class="material-symbols-outlined text-outline">chevron_right</span>
                </a>
                <a href="{{ route('admin.point-exchanges.index', ['status' => 'pending']) }}" class="card flex items-center justify-between p-4 hover:ring-primary/30">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-50 text-error"><span class="material-symbols-outlined">currency_exchange</span></span>
                        <span class="text-sm font-semibold">Proses penukaran</span>
                    </div>
                    <span class="material-symbols-outlined text-outline">chevron_right</span>
                </a>
                <a href="{{ route('admin.rewards.create') }}" class="card flex items-center justify-between p-4 hover:ring-primary/30">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 text-primary"><span class="material-symbols-outlined">redeem</span></span>
                        <span class="text-sm font-semibold">Tambah reward</span>
                    </div>
                    <span class="material-symbols-outlined text-outline">chevron_right</span>
                </a>
                <a href="{{ route('admin.news.create') }}" class="card flex items-center justify-between p-4 hover:ring-primary/30">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-700"><span class="material-symbols-outlined">campaign</span></span>
                        <span class="text-sm font-semibold">Tulis berita</span>
                    </div>
                    <span class="material-symbols-outlined text-outline">chevron_right</span>
                </a>
                <a href="{{ route('admin.waste-categories.create') }}" class="card flex items-center justify-between p-4 hover:ring-primary/30">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-surface-container text-on-surface"><span class="material-symbols-outlined">inventory_2</span></span>
                        <span class="text-sm font-semibold">Tambah kategori</span>
                    </div>
                    <span class="material-symbols-outlined text-outline">chevron_right</span>
                </a>
            </div>
        </div>
    </div>

    <section class="mt-8 flex flex-col items-start justify-between gap-4 rounded-xl border border-outline-variant/50 bg-gradient-to-r from-surface-container-low to-surface-container-high p-5 lg:flex-row lg:items-center">
        <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary-container text-on-primary-container">
                <span class="material-symbols-outlined text-[28px]">receipt_long</span>
            </div>
            <div>
                <h4 class="font-display text-base font-bold">Protokol struk digital warga</h4>
                <p class="text-sm text-on-surface-variant">Setiap penjemputan selesai menghasilkan slip poin dengan nomor seri unik untuk rekonsiliasi.</p>
            </div>
        </div>
        <a href="{{ route('admin.pickup-requests.index') }}" class="btn btn-primary shrink-0">
            <span class="material-symbols-outlined text-base">verified_user</span>
            Buka antrean
        </a>
    </section>
@endsection
