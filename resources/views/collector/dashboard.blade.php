@extends('layouts.collector')

@section('title', 'Manajemen penjemputan')

@section('content')
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <div class="mb-1 flex items-center gap-2 text-xs font-semibold text-slate-500">
                Operasional lapangan
                <span class="material-symbols-outlined text-xs">chevron_right</span>
                <span class="font-bold text-collector-700">Tugas penjemputan</span>
            </div>
            <h1 class="font-display text-2xl font-black tracking-tight text-slate-900">Manajemen penjemputan &amp; timbangan</h1>
        </div>
        <div class="flex items-center gap-2 self-start rounded-xl border border-amber-200/80 bg-amber-50/80 px-3.5 py-2 text-xs font-bold text-amber-900 shadow-2xs">
            <span class="material-symbols-outlined text-base text-collector-600">calendar_today</span>
            {{ now()->timezone('Asia/Jakarta')->translatedFormat('l, d F Y') }}
        </div>
    </div>

    <section class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="kpi-card">
            <div class="mb-1 flex items-center justify-between text-slate-500">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Tugas aktif</span>
                <span class="material-symbols-outlined text-base text-collector-600">checklist</span>
            </div>
            <div class="my-1 flex items-baseline gap-1.5">
                <span class="text-3xl font-black text-slate-900">{{ $stats['assigned'] }}</span>
                <span class="text-xs font-semibold text-slate-500">alamat</span>
            </div>
            <div class="flex items-center gap-3 border-t border-slate-100 pt-2 text-[11px] font-semibold text-amber-800">
                <span>{{ $stats['scheduledToday'] }} jadwal hari ini</span>
            </div>
        </div>
        <div class="kpi-card">
            <div class="mb-1 flex items-center justify-between text-slate-500">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total terkumpul</span>
                <span class="material-symbols-outlined text-base text-emerald-600">inventory_2</span>
            </div>
            <div class="my-1 flex items-baseline gap-1.5">
                <span class="text-3xl font-black text-emerald-700">{{ number_format($stats['totalWeight'], 1) }}</span>
                <span class="text-xs font-semibold text-slate-500">kg</span>
            </div>
            <div class="border-t border-slate-100 pt-2 text-[11px] font-semibold text-emerald-800">Dari penjemputan selesai</div>
        </div>
        <div class="kpi-card">
            <div class="mb-1 flex items-center justify-between text-slate-500">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Selesai dijemput</span>
                <span class="material-symbols-outlined text-base text-collector-600">payments</span>
            </div>
            <div class="my-1 flex items-baseline gap-1.5">
                <span class="text-3xl font-black text-slate-900">{{ $stats['collected'] }}</span>
                <span class="text-xs font-semibold text-slate-500">tugas</span>
            </div>
            <div class="border-t border-slate-100 pt-2 text-[11px] font-medium text-slate-500">Riwayat koleksi Anda</div>
        </div>
        <div class="kpi-card">
            <div class="mb-1 flex items-center justify-between text-slate-500">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Jadwal hari ini</span>
                <span class="material-symbols-outlined text-base text-blue-600">schedule</span>
            </div>
            <div class="my-1 flex items-baseline gap-1.5">
                <span class="text-3xl font-black text-slate-900">{{ $stats['scheduledToday'] }}</span>
                <span class="text-xs font-semibold text-slate-500">slot</span>
            </div>
            <div class="border-t border-slate-100 pt-2 text-[11px] font-semibold text-blue-800">Armada siap</div>
        </div>
    </section>

    <section class="mt-8 grid grid-cols-1 items-start gap-6 lg:grid-cols-12">
        <div class="flex flex-col gap-4 lg:col-span-7">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <h2 class="text-base font-bold text-slate-900">Antrean penjemputan</h2>
                    <span class="rounded-full bg-amber-100 border border-amber-200 px-2 py-0.5 text-xs font-bold text-amber-800">{{ $queue->count() }}</span>
                </div>
                <a href="{{ route('collector.pickup-requests.index') }}" class="text-sm font-bold text-collector-700 hover:underline">Lihat semua</a>
            </div>

            @if ($queue->isEmpty())
                <x-empty-state message="Tidak ada penjemputan yang ditugaskan untuk saat ini." />
            @else
                @foreach ($queue as $pickupRequest)
                    <div class="rounded-2xl border {{ $loop->first ? 'border-collector-300 ring-2 ring-collector-500/10 shadow-md' : 'border-slate-200/80' }} bg-white p-4.5 shadow-sm transition-all">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="mb-1 flex flex-wrap items-center gap-2">
                                    <span class="text-sm font-bold text-slate-900">#TJ-{{ str_pad($pickupRequest->id, 4, '0', STR_PAD_LEFT) }} • {{ $pickupRequest->resident?->name }}</span>
                                    <x-status-badge :status="$pickupRequest->status" :label="$pickupRequest->statusLabel()" />
                                </div>
                                <p class="flex items-center gap-1 text-xs font-medium text-slate-500">
                                    <span class="material-symbols-outlined text-sm text-collector-600">location_on</span>
                                    {{ $pickupRequest->address }}
                                </p>
                            </div>
                            @if ($pickupRequest->scheduled_at)
                                <div class="shrink-0 rounded-xl bg-amber-50 border border-amber-200 px-2.5 py-1 text-right text-xs">
                                    <span class="font-bold text-amber-900">{{ $pickupRequest->scheduled_at->format('H:i') }}</span>
                                    <span class="block text-[10px] font-semibold text-amber-700">{{ $pickupRequest->scheduled_at->format('d M') }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="mt-3 flex flex-wrap gap-1.5">
                            @foreach ($pickupRequest->wasteCategories as $category)
                                <span class="rounded-lg border border-slate-200 bg-slate-50 px-2 py-0.5 text-xs font-semibold text-slate-700">{{ $category->name }}</span>
                            @endforeach
                        </div>
                        <div class="mt-3.5 flex items-center justify-between border-t border-slate-100 pt-3 text-xs">
                            <span class="text-slate-500 italic">{{ $pickupRequest->notes ?: 'Tidak ada catatan tambahan' }}</span>
                            <a href="{{ route('collector.pickup-requests.show', $pickupRequest) }}" class="btn btn-collector btn-sm">
                                <span class="material-symbols-outlined text-sm">scale</span>
                                Input timbangan
                            </a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="flex flex-col gap-4 lg:col-span-5">
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
                    <div class="flex items-center gap-2.5">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-collector-600 border border-amber-200">
                            <span class="material-symbols-outlined text-lg">scale</span>
                        </span>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Input timbangan</h3>
                            <p class="text-[11px] font-medium text-slate-500">Buka tugas untuk mencatat berat riil per kategori</p>
                        </div>
                    </div>
                    <span class="h-2.5 w-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>
                @if ($queue->isNotEmpty())
                    @php $next = $queue->first(); @endphp
                    <p class="mt-3.5 text-xs font-bold uppercase tracking-wider text-slate-400">Order berikutnya</p>
                    <p class="mt-1 text-sm font-bold text-slate-900">#TJ-{{ str_pad($next->id, 4, '0', STR_PAD_LEFT) }} — {{ $next->resident?->name }}</p>
                    <a href="{{ route('collector.pickup-requests.show', $next) }}" class="btn btn-collector mt-4 w-full">
                        <span class="material-symbols-outlined text-base">print</span>
                        Buka form timbang &amp; konfirmasi poin
                    </a>
                @else
                    <p class="mt-3.5 text-sm text-slate-500">Tidak ada order aktif. Antrean akan muncul setelah admin menugaskan penjemputan.</p>
                @endif
            </div>
            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm">
                <div class="mb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined text-base text-collector-600">map</span>
                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Area operasional</h4>
                </div>
                <div class="map-placeholder relative h-36 overflow-hidden rounded-xl border border-amber-200/60 shadow-2xs">
                    <div class="absolute inset-0 flex items-center justify-center text-xs font-bold text-amber-900">Rute harian mengikuti alamat tugas</div>
                </div>
            </div>
        </div>
    </section>
@endsection
