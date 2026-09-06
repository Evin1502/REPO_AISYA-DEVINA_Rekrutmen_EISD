@extends('layouts.admin')

@section('title', 'Dashboard - Admin TemJi')

@section('content')
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">
        <div class="stat-card">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-50 text-2xl">👥</div>
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Resident</p>
                <p class="text-2xl font-bold text-admin-600">{{ number_format($stats['totalResidents']) }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-amber-50 text-2xl">⏳</div>
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Pengajuan Menunggu</p>
                <p class="text-2xl font-bold text-slate-900">{{ number_format($stats['pendingPickups']) }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-50 text-2xl">✅</div>
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Dijemput Hari Ini</p>
                <p class="text-2xl font-bold text-slate-900">{{ number_format($stats['todayCollected']) }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-red-50 text-2xl">🔄</div>
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Penukaran Menunggu</p>
                <p class="text-2xl font-bold text-slate-900">{{ number_format($stats['pendingExchanges']) }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-emerald-50 text-2xl">⭐</div>
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Poin Diberikan</p>
                <p class="text-2xl font-bold text-slate-900">{{ number_format($stats['totalPointsDistributed']) }}</p>
            </div>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 gap-6 xl:grid-cols-3">
        <div class="xl:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-900">Pengajuan Terbaru</h2>
                <a href="{{ route('admin.pickup-requests.index') }}" class="text-sm font-semibold text-admin-600 hover:underline">Lihat Semua</a>
            </div>

            <div class="card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th>#</th>
                                <th>Resident</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentPickupRequests as $pickupRequest)
                                <tr>
                                    <td>{{ $pickupRequest->id }}</td>
                                    <td class="font-medium">{{ $pickupRequest->resident?->name ?? '-' }}</td>
                                    <td>{{ $pickupRequest->created_at->format('d M Y H:i') }}</td>
                                    <td><x-status-badge :status="$pickupRequest->status" /></td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.pickup-requests.show', $pickupRequest) }}" class="btn btn-secondary btn-sm">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-sm text-slate-500">Belum ada pengajuan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div>
            <h2 class="mb-4 text-lg font-bold text-slate-900">Aksi Cepat</h2>
            <div class="space-y-3">
                <a href="{{ route('admin.pickup-requests.index', ['status' => 'pending']) }}" class="card flex items-center justify-between p-4 hover:ring-admin-300">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-50 text-xl">🚚</span>
                        <span class="text-sm font-semibold text-slate-700">Setujui Pengajuan</span>
                    </div>
                    <span class="text-slate-400">→</span>
                </a>
                <a href="{{ route('admin.point-exchanges.index', ['status' => 'pending']) }}" class="card flex items-center justify-between p-4 hover:ring-admin-300">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-50 text-xl">🔄</span>
                        <span class="text-sm font-semibold text-slate-700">Proses Penukaran</span>
                    </div>
                    <span class="text-slate-400">→</span>
                </a>
                <a href="{{ route('admin.rewards.create') }}" class="card flex items-center justify-between p-4 hover:ring-admin-300">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-50 text-xl">🎁</span>
                        <span class="text-sm font-semibold text-slate-700">Tambah Reward</span>
                    </div>
                    <span class="text-slate-400">→</span>
                </a>
                <a href="{{ route('admin.news.create') }}" class="card flex items-center justify-between p-4 hover:ring-admin-300">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-xl">📰</span>
                        <span class="text-sm font-semibold text-slate-700">Tulis Berita</span>
                    </div>
                    <span class="text-slate-400">→</span>
                </a>
                <a href="{{ route('admin.waste-categories.create') }}" class="card flex items-center justify-between p-4 hover:ring-admin-300">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 text-xl">🗑️</span>
                        <span class="text-sm font-semibold text-slate-700">Tambah Kategori</span>
                    </div>
                    <span class="text-slate-400">→</span>
                </a>
            </div>
        </div>
    </div>
@endsection