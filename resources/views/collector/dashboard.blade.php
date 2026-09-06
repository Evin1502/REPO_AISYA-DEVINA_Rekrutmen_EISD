@extends('layouts.collector')

@section('title', 'Dashboard - Kolektor TemJi')

@section('content')
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="stat-card">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-amber-50 text-2xl">📋</div>
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Ditugaskan</p>
                <p class="text-2xl font-bold text-slate-900">{{ $stats['assigned'] }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-50 text-2xl">📅</div>
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Jadwal Hari Ini</p>
                <p class="text-2xl font-bold text-slate-900">{{ $stats['scheduledToday'] }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-50 text-2xl">✅</div>
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Selesai Dijemput</p>
                <p class="text-2xl font-bold text-slate-900">{{ $stats['collected'] }}</p>
            </div>
        </div>
    </div>

    <div class="mt-8">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-900">Antrean Penjemputan</h2>
            <a href="{{ route('collector.pickup-requests.index') }}" class="text-sm font-semibold text-collector-600 hover:underline">Lihat Semua</a>
        </div>

        @if ($queue->isEmpty())
            <x-empty-state message="Tidak ada penjemputan yang ditugaskan untuk saat ini." />
        @else
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                @foreach ($queue as $pickupRequest)
                    <div class="card p-5">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm font-bold text-slate-900">Pengajuan #{{ $pickupRequest->id }}</p>
                                <p class="text-sm text-slate-500">{{ $pickupRequest->resident?->name }}</p>
                            </div>
                            <x-status-badge :status="$pickupRequest->status" />
                        </div>
                        <p class="mt-3 text-sm text-slate-700">{{ $pickupRequest->address }}</p>
                        <div class="mt-3 flex flex-wrap gap-1">
                            @foreach ($pickupRequest->wasteCategories as $category)
                                <span class="inline-flex rounded-full bg-collector-50 px-2 py-0.5 text-xs font-medium text-collector-700">{{ $category->name }}</span>
                            @endforeach
                        </div>
                        @if ($pickupRequest->scheduled_at)
                            <p class="mt-3 text-xs text-slate-500">📅 {{ $pickupRequest->scheduled_at->format('d M Y H:i') }}</p>
                        @endif
                        <a href="{{ route('collector.pickup-requests.show', $pickupRequest) }}" class="btn btn-primary btn-sm mt-4 w-full">Proses Penjemputan</a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection