@extends('layouts.collector')

@section('title', 'Detail Penjemputan #' . $pickupRequest->id . ' - Kolektor TemJi')

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="mb-4 flex items-center justify-between">
            <a href="{{ route('collector.pickup-requests.index') }}" class="text-sm font-semibold text-collector-600 hover:underline">&larr; Kembali ke Antrean</a>
            <x-status-badge :status="$pickupRequest->status" class="text-sm" />
        </div>

        <div class="card">
            <div class="card-header">
                <h1 class="text-lg font-bold text-slate-900">Penjemputan #{{ $pickupRequest->id }}</h1>
            </div>
            <div class="card-body">
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Resident</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-900">{{ $pickupRequest->resident?->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">No. HP</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $pickupRequest->resident?->phone ?? '-' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Alamat</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $pickupRequest->address }}</dd>
                    </div>
                    @if ($pickupRequest->scheduled_at)
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Jadwal</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $pickupRequest->scheduled_at->format('d M Y H:i') }}</dd>
                        </div>
                    @endif
                    @if ($pickupRequest->total_points)
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Total Poin</dt>
                            <dd class="mt-1 text-sm font-bold text-green-600">+{{ number_format($pickupRequest->total_points) }}</dd>
                        </div>
                    @endif
                    @if ($pickupRequest->notes)
                        <div class="sm:col-span-2">
                            <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Catatan Resident</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $pickupRequest->notes }}</dd>
                        </div>
                    @endif
                </dl>

                <h2 class="mb-3 mt-6 text-sm font-bold uppercase tracking-wide text-slate-500">Kategori Sampah</h2>
                <div class="card overflow-hidden">
                    <table class="table w-full">
                        <thead class="bg-slate-50">
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
                    <div class="mt-6 rounded-lg bg-amber-50 p-5 ring-1 ring-amber-200">
                        <h3 class="mb-1 text-sm font-bold text-amber-800">Langkah 1: Jadwalkan Pengambilan</h3>
                        <p class="mb-3 text-xs text-amber-700">Tentukan jadwal penjemputan, lalu tandai status menjadi "Dijadwalkan".</p>
                        <form method="POST" action="{{ route('collector.pickup-requests.status', $pickupRequest) }}" class="flex flex-wrap items-end gap-3">
                            @csrf
                            @method('PATCH')
                            <div class="flex-1">
                                <label for="scheduled_at" class="form-label text-amber-900">Jadwal Pengambilan</label>
                                <input type="datetime-local" id="scheduled_at" name="scheduled_at"
                                       class="form-control @error('scheduled_at') input-error @enderror"
                                       value="{{ old('scheduled_at') }}">
                                @error('scheduled_at')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Tandai Dijadwalkan</button>
                        </form>
                    </div>
                @endif

                @if ($pickupRequest->status === 'scheduled')
                    <div class="mt-6 rounded-lg bg-green-50 p-5 ring-1 ring-green-200">
                        <h3 class="mb-1 text-sm font-bold text-green-800">Langkah 2: Input Berat Riil</h3>
                        <p class="mb-3 text-xs text-green-700">Masukkan berat riil tiap kategori. Poin dihitung otomatis dari berat riil x poin/kg lalu ditambahkan ke saldo resident.</p>
                        <form method="POST" action="{{ route('collector.pickup-requests.status', $pickupRequest) }}" class="space-y-4"
                              onsubmit="return confirm('Selesaikan penjemputan dan hitung poin?')">
                            @csrf
                            @method('PATCH')
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                @foreach ($pickupRequest->wasteCategories as $category)
                                    <div class="rounded-lg bg-white p-3 ring-1 ring-green-100">
                                        <label for="weight-{{ $category->id }}" class="text-sm font-semibold text-slate-800">
                                            {{ $category->name }}
                                            <span class="ml-1 text-xs font-normal text-slate-500">({{ $category->points_per_kg }} poin/kg)</span>
                                        </label>
                                        <input type="number" step="0.1" min="0" id="weight-{{ $category->id }}"
                                               name="actual_weight[{{ $category->id }}]"
                                               placeholder="Berat riil (kg)"
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