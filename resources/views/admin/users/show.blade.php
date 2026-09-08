@extends('layouts.admin')

@section('title', 'Detail ' . $user->name . ' - Admin TemJi')

@section('content')
    <div class="mx-auto max-w-3xl">
        <a href="{{ route('admin.users.index') }}" class="mb-4 inline-block text-sm font-semibold text-admin-600 hover:underline">&larr; Kembali</a>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="stat-card">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-brand-50 text-2xl">⭐</div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-on-surface-variant">Saldo Poin</p>
                    <p class="text-2xl font-bold text-on-surface">{{ number_format($user->points) }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-50 text-2xl">⬆️</div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-on-surface-variant">Total Earn</p>
                    <p class="text-xl font-bold text-on-surface">{{ number_format($totalEarned) }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-red-50 text-2xl">⬇️</div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-on-surface-variant">Total Redeem</p>
                    <p class="text-xl font-bold text-on-surface">{{ number_format($totalRedeemed) }}</p>
                </div>
            </div>
        </div>

        <div class="mt-6 card">
            <div class="card-header">
                <h2 class="text-lg font-bold text-on-surface">Profil</h2>
            </div>
            <div class="card-body">
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-on-surface-variant">Nama</dt>
                        <dd class="mt-1 text-sm text-on-surface">{{ $user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-on-surface-variant">Email</dt>
                        <dd class="mt-1 text-sm text-on-surface">{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-on-surface-variant">Role</dt>
                        <dd class="mt-1 text-sm text-on-surface">{{ ucfirst($user->role) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-on-surface-variant">No. HP</dt>
                        <dd class="mt-1 text-sm text-on-surface">{{ $user->phone ?? '-' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-medium uppercase tracking-wide text-on-surface-variant">Alamat</dt>
                        <dd class="mt-1 text-sm text-on-surface">{{ $user->address ?? '-' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="mt-6">
            <h2 class="mb-4 text-lg font-bold text-on-surface">Pengajuan Terbaru</h2>
            @if ($user->pickupRequests->isEmpty())
                <x-empty-state message="Belum ada pengajuan." class="py-6" />
            @else
                <div class="card overflow-hidden">
                    <table class="table w-full">
                        <thead class="bg-surface-container-low/80">
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Poin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user->pickupRequests as $pickupRequest)
                                <tr>
                                    <td>{{ $pickupRequest->id }}</td>
                                    <td>{{ $pickupRequest->created_at->format('d M Y H:i') }}</td>
                                    <td><x-status-badge :status="$pickupRequest->status" :label="$pickupRequest->statusLabel()" /></td>
                                    <td>{{ $pickupRequest->total_points ? '+' . number_format($pickupRequest->total_points) : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="mt-6">
            <h2 class="mb-4 text-lg font-bold text-on-surface">Riwayat Poin</h2>
            @if ($user->pointHistories->isEmpty())
                <x-empty-state message="Belum ada riwayat poin." class="py-6" />
            @else
                <div class="card overflow-hidden">
                    <table class="table w-full">
                        <thead class="bg-surface-container-low/80">
                            <tr>
                                <th>Deskripsi</th>
                                <th>Tipe</th>
                                <th class="text-right">Poin</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user->pointHistories as $history)
                                <tr>
                                    <td>{{ $history->description }}</td>
                                    <td><x-status-badge :status="$history->type" /></td>
                                    <td class="text-right font-bold {{ $history->points > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $history->points > 0 ? '+' : '' }}{{ number_format($history->points) }}
                                    </td>
                                    <td>{{ $history->created_at->format('d M Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection