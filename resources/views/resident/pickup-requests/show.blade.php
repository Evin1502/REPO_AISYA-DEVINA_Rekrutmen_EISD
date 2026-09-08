@extends('layouts.app')

@section('title', 'Detail Pengajuan #' . $pickupRequest->id . ' - TemJi')

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="mb-4 flex items-center justify-between">
            <a href="{{ route('resident.pickup-requests.index') }}" class="text-sm font-semibold text-brand-600 hover:underline">&larr; Kembali</a>
            <x-status-badge :status="$pickupRequest->status" :label="$pickupRequest->statusLabel()" class="text-sm" />
        </div>

        <div class="card">
            <div class="card-header">
                <h1 class="text-lg font-bold text-on-surface">Detail Pengajuan #{{ $pickupRequest->id }}</h1>
            </div>
            <div class="card-body">
                <div class="mb-6">
                    <x-pickup-status-stepper :status="$pickupRequest->status" />
                </div>

                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-on-surface-variant">Alamat Penjemputan</dt>
                        <dd class="mt-1 text-sm text-on-surface">{{ $pickupRequest->address }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-on-surface-variant">Diajukan Pada</dt>
                        <dd class="mt-1 text-sm text-on-surface">{{ $pickupRequest->created_at->format('d M Y H:i') }}</dd>
                    </div>
                    @if ($pickupRequest->scheduled_at)
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-on-surface-variant">Tanggal Penjemputan</dt>
                            <dd class="mt-1 text-sm text-on-surface">{{ $pickupRequest->scheduled_at->format('d M Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-on-surface-variant">Slot Waktu</dt>
                            <dd class="mt-1 text-sm text-on-surface">{{ $pickupRequest->timeSlotLabel() ?? '–' }}</dd>
                        </div>
                    @endif
                    @if ($pickupRequest->collector)
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-on-surface-variant">Kolektor</dt>
                            <dd class="mt-1 text-sm text-on-surface">{{ $pickupRequest->collector->name }}</dd>
                        </div>
                    @endif
                    @if ($pickupRequest->total_weight)
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-on-surface-variant">Total Berat</dt>
                            <dd class="mt-1 text-sm text-on-surface">{{ $pickupRequest->total_weight }} kg</dd>
                        </div>
                    @endif
                    @if ($pickupRequest->total_points)
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-on-surface-variant">Total Poin</dt>
                            <dd class="mt-1 text-sm font-bold text-green-600">+{{ number_format($pickupRequest->total_points) }} poin</dd>
                        </div>
                    @endif
                    @if ($pickupRequest->notes)
                        <div class="sm:col-span-2">
                            <dt class="text-xs font-medium uppercase tracking-wide text-on-surface-variant">Catatan</dt>
                            <dd class="mt-1 text-sm text-on-surface">{{ $pickupRequest->notes }}</dd>
                        </div>
                    @endif
                </dl>

                <h2 class="mb-3 mt-6 text-sm font-bold uppercase tracking-wide text-on-surface-variant">Kategori Sampah</h2>
                <div class="card overflow-hidden">
                    <table class="table w-full">
                        <thead class="bg-surface-container-low/80">
                            <tr>
                                <th>Kategori</th>
                                <th>Berat Estimasi</th>
                                <th>Berat Riil</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pickupRequest->wasteCategories as $category)
                                <tr>
                                    <td class="font-medium">{{ $category->name }}</td>
                                    <td>{{ $category->pivot->estimated_weight }} kg</td>
                                    <td>{{ $category->pivot->actual_weight ? $category->pivot->actual_weight . ' kg' : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($pickupRequest->status === 'pending')
                    <form method="POST" action="{{ route('resident.pickup-requests.destroy', $pickupRequest) }}"
                          class="mt-6" onsubmit="return confirm('Batalkan pengajuan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Batalkan Pengajuan</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection