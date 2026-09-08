@extends('layouts.collector')

@section('title', 'Detail Penjemputan #' . $pickupRequest->id . ' - Kolektor TemJi')

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="mb-4 flex items-center justify-between">
            <a href="{{ route('collector.pickup-requests.index') }}" class="text-sm font-semibold text-collector-600 hover:underline">&larr; Kembali ke Antrean</a>
            <x-status-badge :status="$pickupRequest->status" :label="$pickupRequest->statusLabel()" class="text-sm" />
        </div>

        <div class="card">
            <div class="card-header">
                <h1 class="text-lg font-bold text-on-surface">Penjemputan #{{ $pickupRequest->id }}</h1>
            </div>
            <div class="card-body">
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-on-surface-variant">Resident</dt>
                        <dd class="mt-1 text-sm font-semibold text-on-surface">{{ $pickupRequest->resident?->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-on-surface-variant">No. HP</dt>
                        <dd class="mt-1 text-sm text-on-surface">{{ $pickupRequest->resident?->phone ?? '-' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-medium uppercase tracking-wide text-on-surface-variant">Alamat</dt>
                        <dd class="mt-1 text-sm text-on-surface">{{ $pickupRequest->address }}</dd>
                    </div>
                    @if ($pickupRequest->scheduled_at)
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-on-surface-variant">Jadwal</dt>
                            <dd class="mt-1 text-sm text-on-surface">{{ $pickupRequest->scheduled_at->format('d M Y H:i') }}</dd>
                        </div>
                    @endif
                    @if ($pickupRequest->total_points)
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-on-surface-variant">Total Poin</dt>
                            <dd class="mt-1 text-sm font-bold text-green-600">+{{ number_format($pickupRequest->total_points) }}</dd>
                        </div>
                    @endif
                    @if ($pickupRequest->notes)
                        <div class="sm:col-span-2">
                            <dt class="text-xs font-medium uppercase tracking-wide text-on-surface-variant">Catatan Resident</dt>
                            <dd class="mt-1 text-sm text-on-surface">{{ $pickupRequest->notes }}</dd>
                        </div>
                    @endif
                </dl>

                <h2 class="mb-3 mt-6 text-sm font-bold uppercase tracking-wide text-on-surface-variant">Kategori Sampah</h2>
                <div class="card overflow-hidden">
                    <table class="table w-full">
                        <thead class="bg-surface-container-low">
                            <tr>
                                <th>Kategori</th>
                                <th>Poin / kg</th>
                                <th>Berat Estimasi</th>
                                <th>Berat Riil</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pickupRequest->wasteCategories as $category)
                                <tr>
                                    <td class="font-medium">{{ $category->name }}</td>
                                    <td>{{ $category->points_per_kg }}</td>
                                    <td>{{ $category->pivot->estimated_weight }} kg</td>
                                    <td>{{ $category->pivot->actual_weight ? $category->pivot->actual_weight . ' kg' : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($pickupRequest->status === 'approved')
                    <div class="mt-6 rounded-lg bg-green-50 p-5 ring-1 ring-green-200">
                        <h3 class="mb-1 text-sm font-bold text-green-800">Selesaikan Penjemputan</h3>
                        <p class="mb-3 text-xs text-green-700">
                            Jadwal sudah ditentukan warga saat mengajukan{{ $pickupRequest->scheduled_at ? ' (' . $pickupRequest->scheduled_at->format('d M Y H:i') . ($pickupRequest->timeSlotLabel() ? ', slot ' . $pickupRequest->timeSlotLabel() : '') . ')' : '' }}.
                            Masukkan berat riil tiap kategori setelah barang ditimbang. Poin dihitung otomatis dari berat riil x poin/kg lalu ditambahkan ke saldo resident.
                        </p>
                        <form method="POST" action="{{ route('collector.pickup-requests.status', $pickupRequest) }}" class="space-y-4"
                              onsubmit="return confirm('Selesaikan penjemputan dan hitung poin?')">
                            @csrf
                            @method('PATCH')
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                @foreach ($pickupRequest->wasteCategories as $category)
                                    <div class="rounded-lg bg-white p-3 ring-1 ring-green-100">
                                        <label for="weight-{{ $category->id }}" class="text-sm font-semibold text-on-surface">
                                            {{ $category->name }}
                                            <span class="ml-1 text-xs font-normal text-on-surface-variant">({{ $category->points_per_kg }} poin/kg)</span>
                                        </label>
                                        <input type="number" step="0.1" min="0" id="weight-{{ $category->id }}"
                                               name="actual_weight[{{ $category->id }}]"
                                               placeholder="Berat riil (kg)"
                                               required
                                               class="weight-input mt-2 form-control @error('actual_weight.' . $category->id) input-error @enderror">
                                        @error('actual_weight.' . $category->id)
                                            <div class="form-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endforeach
                            </div>
                            @error('actual_weight')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                            <button type="submit" class="btn btn-primary w-full">Selesaikan & Hitung Poin</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection