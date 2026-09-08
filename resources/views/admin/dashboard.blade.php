@extends('layouts.admin')

@section('title', 'Ringkasan operasional')

@section('content')
    <section class="flex flex-col gap-4">
        <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
            <div>
                <h1 class="font-display text-xl font-bold tracking-tight text-slate-900">Kinerja sirkularitas &amp; operasional</h1>
                <p class="text-sm text-slate-500">Metrik penjemputan, timbangan, dan distribusi reward warga.</p>
            </div>
            <div class="inline-flex items-center gap-1.5 self-start rounded-full bg-admin-50 border border-admin-200 px-3 py-1.5 text-xs font-bold text-admin-700 sm:self-auto shadow-2xs">
                <span class="material-symbols-outlined text-base">verified</span>
                Data live dari penjemputan terverifikasi
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="kpi-card">
                <div class="mb-2 flex items-center justify-between text-slate-500">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total sampah terolah</span>
                    <span class="material-symbols-outlined text-admin-600">recycling</span>
                </div>
                <div class="my-1 flex items-baseline gap-1">
                    <span class="font-display text-4xl font-black text-slate-900">{{ number_format($stats['totalWeight'] / 1000, 2) }}</span>
                    <span class="text-sm font-bold text-slate-500">ton</span>
                </div>
                <div class="mt-3 flex items-center justify-between border-t border-slate-100 pt-2 text-[11px] font-medium text-slate-500">
                    <span>{{ number_format($stats['totalWeight'], 1) }} kg</span>
                    <span class="text-emerald-700 font-semibold">{{ number_format($stats['todayCollected']) }} selesai hari ini</span>
                </div>
            </div>
            <div class="kpi-card">
                <div class="mb-2 flex items-center justify-between text-slate-500">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Warga terdaftar</span>
                    <span class="material-symbols-outlined text-indigo-500">people</span>
                </div>
                <div class="my-1 flex items-baseline gap-1">
                    <span class="font-display text-4xl font-black text-slate-900">{{ number_format($stats['totalResidents']) }}</span>
                    <span class="text-sm font-bold text-slate-500">KK</span>
                </div>
                <div class="mt-3 border-t border-slate-100 pt-2 text-[11px] font-medium text-slate-500">Akun role resident</div>
            </div>
            <div class="kpi-card">
                <div class="mb-2 flex items-center justify-between text-slate-500">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Armada mitra</span>
                    <span class="material-symbols-outlined text-amber-600">electric_rickshaw</span>
                </div>
                <div class="my-1 flex items-baseline gap-1">
                    <span class="font-display text-4xl font-black text-slate-900">{{ number_format($stats['totalCollectors']) }}</span>
                    <span class="text-sm font-bold text-slate-500">kolektor</span>
                </div>
                <div class="mt-3 border-t border-slate-100 pt-2 text-[11px] text-amber-700 font-bold">{{ number_format($stats['pendingPickups']) }} menunggu persetujuan</div>
            </div>
            <div class="kpi-card">
                <div class="mb-2 flex items-center justify-between text-slate-500">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Reward disalurkan</span>
                    <span class="material-symbols-outlined text-amber-600">payments</span>
                </div>
                <div class="my-1 flex items-baseline gap-1">
                    <span class="text-base font-bold text-slate-400">Rp</span>
                    <span class="font-display text-3xl font-black text-slate-900 sm:text-4xl">{{ number_format($stats['approvedSaldoValue'], 0, ',', '.') }}</span>
                </div>
                <div class="mt-3 flex items-center justify-between border-t border-slate-100 pt-2 text-[11px]">
                    <span class="font-bold text-amber-700">⭐ {{ number_format($stats['totalPointsDistributed']) }} pts</span>
                    <span class="text-slate-500 font-medium">{{ number_format($stats['pendingExchanges']) }} menunggu</span>
                </div>
            </div>
        </div>
    </section>

    <section class="mt-8 grid grid-cols-1 gap-6 xl:grid-cols-3">
        <div class="kpi-card xl:col-span-1">
            <div class="mb-2 flex items-center justify-between text-slate-500">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Ketepatan waktu</span>
                <span class="material-symbols-outlined text-admin-600">schedule</span>
            </div>
            @if ($stats['onTimeRate'] === null)
                <div class="my-1">
                    <span class="font-display text-2xl font-bold text-slate-400">Belum ada data</span>
                </div>
                <div class="mt-3 border-t border-slate-100 pt-2 text-[11px] text-slate-500">
                    Muncul setelah ada penjemputan berjadwal yang selesai
                </div>
            @else
                <div class="my-1 flex items-baseline gap-1">
                    <span class="font-display text-4xl font-black text-slate-900">{{ number_format($stats['onTimeRate'], 1) }}</span>
                    <span class="text-sm font-bold text-slate-500">%</span>
                </div>
                <div class="mt-3 border-t border-slate-100 pt-2 text-[11px] text-slate-500 font-medium">
                    Selesai ≤ 60 menit dari jadwal
                </div>
            @endif
        </div>

        <div class="card p-5 xl:col-span-2">
            <div class="mb-3 flex items-center justify-between">
                <div>
                    <h2 class="font-display text-base font-bold text-slate-900">Sebaran sampah terolah per wilayah</h2>
                    <p class="text-xs text-slate-500">Cakupan pengelolaan sampah lintas wilayah kota (SDG 11.6)</p>
                </div>
                <span class="material-symbols-outlined text-slate-400">map</span>
            </div>

            @if ($areaBreakdown->isEmpty())
                <p class="py-6 text-center text-sm text-slate-500">
                    Belum ada penjemputan selesai dengan data wilayah.
                </p>
            @else
                @php $maxWeight = $areaBreakdown->max('total_weight') ?: 1; @endphp
                <div class="flex flex-col gap-3">
                    @foreach ($areaBreakdown as $row)
                        <div>
                            <div class="mb-1 flex items-center justify-between text-xs">
                                <span class="font-bold text-slate-800">{{ $row->area }}</span>
                                <span class="text-slate-500 font-medium">
                                    {{ number_format($row->total_weight, 1) }} kg · {{ number_format($row->total_pickups) }} penjemputan
                                </span>
                            </div>
                            <div class="h-2.5 w-full overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full bg-admin-600" style="width: {{ max(4, round($row->total_weight / $maxWeight * 100)) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <div class="mt-8 grid grid-cols-1 gap-6 xl:grid-cols-3">
        <div class="xl:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h2 class="font-display text-lg font-bold text-slate-900">Antrean penjemputan terkini</h2>
                    <p class="text-xs text-slate-500">Mutasi pengajuan warga dan status alur operasional.</p>
                </div>
                <a href="{{ route('admin.pickup-requests.index') }}" class="text-sm font-bold text-admin-700 hover:underline">Lihat semua</a>
            </div>
            <div class="card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead class="bg-slate-50">
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
                                <tr class="hover:bg-slate-50/70">
                                    <td>
                                        <span class="block font-mono font-bold text-admin-700">TJ-{{ str_pad($pickupRequest->id, 4, '0', STR_PAD_LEFT) }}</span>
                                        <span class="text-[11px] text-slate-400 font-medium">{{ $pickupRequest->created_at->format('H:i') }} WIB</span>
                                    </td>
                                    <td class="font-semibold text-slate-800">{{ $pickupRequest->resident?->name ?? '-' }}</td>
                                    <td class="text-slate-600">{{ $pickupRequest->collector?->name ?? '—' }}</td>
                                    <td class="text-slate-600">{{ $pickupRequest->created_at->format('d M Y') }}</td>
                                    <td><x-status-badge :status="$pickupRequest->status" :label="$pickupRequest->statusLabel()" /></td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.pickup-requests.show', $pickupRequest) }}" class="btn btn-admin btn-sm">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-sm text-slate-500">Belum ada pengajuan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div>
            <h2 class="mb-4 font-display text-lg font-bold text-slate-900">Aksi cepat</h2>
            <div class="space-y-3">
                <a href="{{ route('admin.pickup-requests.index', ['status' => 'pending']) }}" class="card flex items-center justify-between p-4 hover:ring-2 hover:ring-admin-500/20 hover:shadow-md">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-700 border border-amber-200"><span class="material-symbols-outlined">local_shipping</span></span>
                        <span class="text-sm font-bold text-slate-800">Setujui pengajuan</span>
                    </div>
                    <span class="material-symbols-outlined text-slate-400">chevron_right</span>
                </a>
                <a href="{{ route('admin.point-exchanges.index', ['status' => 'pending']) }}" class="card flex items-center justify-between p-4 hover:ring-2 hover:ring-admin-500/20 hover:shadow-md">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-50 text-red-600 border border-red-200"><span class="material-symbols-outlined">currency_exchange</span></span>
                        <span class="text-sm font-bold text-slate-800">Proses penukaran</span>
                    </div>
                    <span class="material-symbols-outlined text-slate-400">chevron_right</span>
                </a>
                <a href="{{ route('admin.rewards.create') }}" class="card flex items-center justify-between p-4 hover:ring-2 hover:ring-admin-500/20 hover:shadow-md">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200"><span class="material-symbols-outlined">redeem</span></span>
                        <span class="text-sm font-bold text-slate-800">Tambah reward</span>
                    </div>
                    <span class="material-symbols-outlined text-slate-400">chevron_right</span>
                </a>
                <a href="{{ route('admin.news.create') }}" class="card flex items-center justify-between p-4 hover:ring-2 hover:ring-admin-500/20 hover:shadow-md">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-700 border border-blue-200"><span class="material-symbols-outlined">campaign</span></span>
                        <span class="text-sm font-bold text-slate-800">Tulis berita</span>
                    </div>
                    <span class="material-symbols-outlined text-slate-400">chevron_right</span>
                </a>
                <a href="{{ route('admin.waste-categories.create') }}" class="card flex items-center justify-between p-4 hover:ring-2 hover:ring-admin-500/20 hover:shadow-md">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-700 border border-slate-200"><span class="material-symbols-outlined">inventory_2</span></span>
                        <span class="text-sm font-bold text-slate-800">Tambah kategori</span>
                    </div>
                    <span class="material-symbols-outlined text-slate-400">chevron_right</span>
                </a>
            </div>
        </div>
    </div>

    <section class="mt-8 flex flex-col items-start justify-between gap-4 rounded-2xl border border-admin-200 bg-gradient-to-r from-admin-50 via-white to-indigo-50/50 p-6 shadow-sm lg:flex-row lg:items-center">
        <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-admin-600 text-white shadow-sm shadow-admin-600/20">
                <span class="material-symbols-outlined text-[28px]">receipt_long</span>
            </div>
            <div>
                <h4 class="font-display text-base font-bold text-slate-900">Protokol struk digital warga</h4>
                <p class="text-sm text-slate-600">Setiap penjemputan selesai menghasilkan slip poin dengan nomor seri unik untuk rekonsiliasi.</p>
            </div>
        </div>
        <a href="{{ route('admin.pickup-requests.index') }}" class="btn btn-admin shrink-0">
            <span class="material-symbols-outlined text-base">verified_user</span>
            Buka antrean
        </a>
    </section>
@endsection
