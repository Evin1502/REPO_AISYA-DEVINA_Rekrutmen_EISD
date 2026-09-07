@extends('layouts.collector')

@section('title', 'Manajemen penjemputan')

@section('content')
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <div class="mb-1 flex items-center gap-2 text-xs font-medium text-on-surface-variant">
                Operasional lapangan
                <span class="material-symbols-outlined text-xs">chevron_right</span>
                <span class="font-semibold text-primary">Tugas penjemputan</span>
            </div>
            <h1 class="font-display text-2xl font-black tracking-tight">Manajemen penjemputan &amp; timbangan</h1>
        </div>
        <div class="flex items-center gap-2 self-start rounded-lg border border-outline-variant/40 bg-surface-container-high px-3 py-2 text-xs font-semibold">
            <span class="material-symbols-outlined text-base text-primary">calendar_today</span>
            {{ now()->timezone('Asia/Jakarta')->translatedFormat('l, d F Y') }}
        </div>
    </div>

    <section class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="kpi-card">
            <div class="mb-1 flex items-center justify-between text-on-surface-variant">
                <span class="text-xs font-medium">Tugas aktif</span>
                <span class="material-symbols-outlined text-base text-primary">checklist</span>
            </div>
            <div class="my-1 flex items-baseline gap-1.5">
                <span class="text-2xl font-bold">{{ $stats['assigned'] }}</span>
                <span class="text-xs text-on-surface-variant">alamat</span>
            </div>
            <div class="flex items-center gap-3 border-t border-outline-variant/20 pt-2 text-[11px] text-on-surface-variant">
                <span class="font-medium text-emerald-700">{{ $stats['scheduledToday'] }} jadwal hari ini</span>
            </div>
        </div>
        <div class="kpi-card">
            <div class="mb-1 flex items-center justify-between text-on-surface-variant">
                <span class="text-xs font-medium">Total terkumpul</span>
                <span class="material-symbols-outlined text-base text-emerald-700">inventory_2</span>
            </div>
            <div class="my-1 flex items-baseline gap-1.5">
                <span class="text-2xl font-bold text-primary">{{ number_format($stats['totalWeight'], 1) }}</span>
                <span class="text-xs text-on-surface-variant">kg</span>
            </div>
            <div class="border-t border-outline-variant/20 pt-2 text-[11px] font-medium text-emerald-700">Dari penjemputan selesai</div>
        </div>
        <div class="kpi-card">
            <div class="mb-1 flex items-center justify-between text-on-surface-variant">
                <span class="text-xs font-medium">Selesai dijemput</span>
                <span class="material-symbols-outlined text-base text-amber-600">payments</span>
            </div>
            <div class="my-1 flex items-baseline gap-1.5">
                <span class="text-2xl font-bold">{{ $stats['collected'] }}</span>
                <span class="text-xs text-on-surface-variant">tugas</span>
            </div>
            <div class="border-t border-outline-variant/20 pt-2 text-[11px] text-on-surface-variant">Riwayat koleksi Anda</div>
        </div>
        <div class="kpi-card">
            <div class="mb-1 flex items-center justify-between text-on-surface-variant">
                <span class="text-xs font-medium">Jadwal hari ini</span>
                <span class="material-symbols-outlined text-base text-blue-700">schedule</span>
            </div>
            <div class="my-1 flex items-baseline gap-1.5">
                <span class="text-2xl font-bold">{{ $stats['scheduledToday'] }}</span>
                <span class="text-xs text-on-surface-variant">slot</span>
            </div>
            <div class="border-t border-outline-variant/20 pt-2 text-[11px] font-medium text-emerald-700">Armada siap</div>
        </div>
    </section>

    <section class="mt-8 grid grid-cols-1 items-start gap-6 lg:grid-cols-12">
        <div class="flex flex-col gap-4 lg:col-span-7">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <h2 class="text-base font-bold">Antrean penjemputan</h2>
                    <span class="rounded-full bg-surface-container px-2 py-0.5 text-xs font-semibold text-on-surface-variant">{{ $queue->count() }}</span>
                </div>
                <a href="{{ route('collector.pickup-requests.index') }}" class="text-sm font-semibold text-primary hover:underline">Lihat semua</a>
            </div>

            @if ($queue->isEmpty())
                <x-empty-state message="Tidak ada penjemputan yang ditugaskan untuk saat ini." />
            @else
                @foreach ($queue as $pickupRequest)
                    <div class="rounded-xl border {{ $loop->first ? 'border-primary/40 shadow-sm' : 'border-outline-variant/30' }} bg-surface-container-lowest p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="mb-0.5 flex flex-wrap items-center gap-2">
                                    <span class="text-sm font-bold">#TJ-{{ str_pad($pickupRequest->id, 4, '0', STR_PAD_LEFT) }} • {{ $pickupRequest->resident?->name }}</span>
                                    <x-status-badge :status="$pickupRequest->status" />
                                </div>
                                <p class="flex items-center gap-1 text-xs text-on-surface-variant">
                                    <span class="material-symbols-outlined text-sm text-primary">location_on</span>
                                    {{ $pickupRequest->address }}
                                </p>
                            </div>
                            @if ($pickupRequest->scheduled_at)
                                <div class="shrink-0 text-right text-xs">
                                    <span class="font-semibold">{{ $pickupRequest->scheduled_at->format('H:i') }}</span>
                                    <span class="block text-[10px] text-on-surface-variant">{{ $pickupRequest->scheduled_at->format('d M') }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="mt-3 flex flex-wrap gap-1.5">
                            @foreach ($pickupRequest->wasteCategories as $category)
                                <span class="rounded-md bg-surface-container-low px-2 py-1 text-xs">{{ $category->name }}</span>
                            @endforeach
                        </div>
                        <div class="mt-3 flex items-center justify-between border-t border-outline-variant/20 pt-3 text-xs">
                            <span class="text-on-surface-variant">{{ $pickupRequest->notes ?: 'Tidak ada catatan tambahan' }}</span>
                            <a href="{{ route('collector.pickup-requests.show', $pickupRequest) }}" class="btn btn-primary btn-sm">
                                <span class="material-symbols-outlined text-sm">scale</span>
                                Input timbangan
                            </a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="flex flex-col gap-4 lg:col-span-5">
            <div class="rounded-xl border border-outline-variant/30 bg-surface-container-lowest p-4 shadow-sm">
                <div class="flex items-center justify-between border-b border-outline-variant/20 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">scale</span>
                        <div>
                            <h3 class="text-sm font-bold">Input timbangan</h3>
                            <p class="text-[11px] text-on-surface-variant">Buka tugas untuk mencatat berat riil per kategori</p>
                        </div>
                    </div>
                    <span class="h-2 w-2 rounded-full bg-emerald-600"></span>
                </div>
                @if ($queue->isNotEmpty())
                    @php $next = $queue->first(); @endphp
                    <p class="mt-3 text-xs text-on-surface-variant">Order berikutnya</p>
                    <p class="mt-1 text-sm font-semibold">#TJ-{{ str_pad($next->id, 4, '0', STR_PAD_LEFT) }} — {{ $next->resident?->name }}</p>
                    <a href="{{ route('collector.pickup-requests.show', $next) }}" class="btn btn-primary mt-4 w-full">
                        <span class="material-symbols-outlined text-base">print</span>
                        Buka form timbang &amp; konfirmasi poin
                    </a>
                @else
                    <p class="mt-3 text-sm text-on-surface-variant">Tidak ada order aktif. Antrean akan muncul setelah admin menugaskan penjemputan.</p>
                @endif
            </div>
            <div class="rounded-xl border border-outline-variant/30 bg-surface-container-lowest p-3.5 shadow-sm">
                <div class="mb-2 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-base text-primary">map</span>
                    <h4 class="text-xs font-bold">Area operasional</h4>
                </div>
                <div class="map-placeholder relative h-36 overflow-hidden rounded-lg border border-outline-variant/30">
                    <div class="absolute inset-0 flex items-center justify-center text-xs font-semibold text-primary">Rute harian mengikuti alamat tugas</div>
                </div>
            </div>
        </div>
    </section>
@endsection
