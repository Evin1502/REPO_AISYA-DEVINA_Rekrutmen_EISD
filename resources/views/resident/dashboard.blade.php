@extends('layouts.app')

@section('title', 'Beranda - TemJi')

@section('content')
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">
        <div class="stat-card">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-brand-50 text-2xl">⭐</div>
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Saldo Poin</p>
                <p class="text-2xl font-bold text-brand-600">{{ number_format($pointsBalance) }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-50 text-2xl">💰</div>
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Saldo Cash</p>
                <p class="text-2xl font-bold text-green-600">{{ $cashBalanceLabel }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-amber-50 text-2xl">⏳</div>
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Pengajuan Menunggu</p>
                <p class="text-2xl font-bold text-slate-900">{{ $pendingPickups }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-50 text-2xl">🔔</div>
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Notifikasi Baru</p>
                <p class="text-2xl font-bold text-slate-900">{{ $unreadNotifications }}</p>
            </div>
        </div>
        <a href="{{ route('resident.pickup-requests.create') }}" class="stat-card hover:ring-brand-300">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-brand-600 text-2xl text-white">＋</div>
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Aksi Cepat</p>
                <p class="text-lg font-bold text-brand-600">Ajukan Penjemputan</p>
            </div>
        </a>
    </div>

    <div class="mt-8">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-900">Aktivitas Poin Terbaru</h2>
            <a href="{{ route('resident.point-histories.index') }}" class="text-sm font-semibold text-brand-600 hover:underline">Lihat Semua</a>
        </div>

        @if ($recentHistories->isEmpty())
            <x-empty-state message="Belum ada aktivitas poin. Ajukan penjemputan sampah untuk mulai mengumpulkan poin!"
                           :action-route="route('resident.pickup-requests.create')" action-label="Ajukan Penjemputan" />
        @else
            <div class="card divide-y divide-slate-100">
                @foreach ($recentHistories as $history)
                    <div class="flex items-center justify-between px-5 py-3">
                        <div>
                            <p class="text-sm font-medium text-slate-900">{{ $history->description }}</p>
                            <p class="text-xs text-slate-500">{{ $history->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="{{ $history->points > 0 ? 'text-green-600' : 'text-red-600' }} text-sm font-bold">
                                {{ $history->points > 0 ? '+' : '' }}{{ number_format($history->points) }}
                            </p>
                            <x-status-badge :status="$history->type" class="mt-1" />
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection