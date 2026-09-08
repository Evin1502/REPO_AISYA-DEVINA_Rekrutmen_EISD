@extends('layouts.admin')

@section('title', 'Detail Pengajuan #' . $pickupRequest->id . ' - Admin TemJi')

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="mb-4 flex items-center justify-between">
            <a href="{{ route('admin.pickup-requests.index') }}" class="text-sm font-semibold text-admin-600 hover:underline">&larr; Kembali</a>
            <x-status-badge :status="$pickupRequest->status" :label="$pickupRequest->statusLabel()" class="text-sm" />
        </div>

        <div class="card">
            <div class="card-header">
                <h1 class="text-lg font-bold text-on-surface">Pengajuan #{{ $pickupRequest->id }}</h1>
            </div>
            <div class="card-body">
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-on-surface-variant">Resident</dt>
                        <dd class="mt-1 text-sm font-semibold text-on-surface">{{ $pickupRequest->resident?->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-on-surface-variant">Email Resident</dt>
                        <dd class="mt-1 text-sm text-on-surface">{{ $pickupRequest->resident?->email ?? '-' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-medium uppercase tracking-wide text-on-surface-variant">Alamat</dt>
                        <dd class="mt-1 text-sm text-on-surface">{{ $pickupRequest->address }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-on-surface-variant">Kolektor</dt>
                        <dd class="mt-1 text-sm text-on-surface">{{ $pickupRequest->collector?->name ?? 'Belum ditugaskan' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-on-surface-variant">Diajukan</dt>
                        <dd class="mt-1 text-sm text-on-surface">{{ $pickupRequest->created_at->format('d M Y H:i') }}</dd>
                    </div>
                    @if ($pickupRequest->scheduled_at)
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-on-surface-variant">Tanggal Penjemputan</dt>
                            <dd class="mt-1 text-sm text-on-surface">{{ $pickupRequest->scheduled_at->format('d M Y') }}</dd>
                        </div>
                    @endif
                    @if ($pickupRequest->time_slot)
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-on-surface-variant">Slot Waktu</dt>
                            <dd class="mt-1 text-sm text-on-surface">{{ $pickupRequest->timeSlotLabel() ?? '–' }}</dd>
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
                            <dd class="mt-1 text-sm font-bold text-green-600">+{{ number_format($pickupRequest->total_points) }}</dd>
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

                @if ($pickupRequest->status === 'pending')
                    <div class="mt-6 rounded-lg bg-surface-container-low p-5">
                        <h3 class="mb-3 text-sm font-bold text-on-surface">Proses Pengajuan</h3>
                        <form method="POST" action="{{ route('admin.pickup-requests.approve', $pickupRequest) }}" class="space-y-3">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label for="collector_id" class="form-label">Tugaskan Collector / Petugas <span class="text-red-500">*</span></label>
                                <select id="collector_id" name="collector_id" required class="form-select @error('collector_id') input-error @enderror">
                                    <option value="">Pilih collector ...</option>
                                    @foreach ($collectors as $collector)
                                        <option value="{{ $collector->id }}" @selected(old('collector_id') == $collector->id)>{{ $collector->name }}</option>
                                    @endforeach
                                </select>
                                @error('collector_id')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <p class="form-text">Jadwal penjemputan sudah dipilih Resident (tanggal & slot waktu). Setelah ditugaskan, status menjadi "Ditugaskan".</p>
                            <div class="flex gap-2">
                                <button type="submit" class="btn btn-primary btn-sm">Setujui & Tugaskan</button>
                            </div>
                        </form>

                        <form method="POST" action="{{ route('admin.pickup-requests.reject', $pickupRequest) }}" class="mt-3"
                              onsubmit="return confirm('Tolak pengajuan ini?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger btn-sm">Tolak Pengajuan</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection