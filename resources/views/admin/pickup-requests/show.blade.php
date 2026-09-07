@extends('layouts.admin')

@section('title', 'Detail Pengajuan #' . $pickupRequest->id . ' - Admin TemJi')

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="mb-4 flex items-center justify-between">
            <a href="{{ route('admin.pickup-requests.index') }}" class="text-sm font-semibold text-admin-600 hover:underline">&larr; Kembali</a>
            <x-status-badge :status="$pickupRequest->status" class="text-sm" />
        </div>

        <div class="card">
            <div class="card-header">
                <h1 class="text-lg font-bold text-slate-900">Pengajuan #{{ $pickupRequest->id }}</h1>
            </div>
            <div class="card-body">
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Resident</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-900">{{ $pickupRequest->resident?->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Email Resident</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $pickupRequest->resident?->email ?? '-' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Alamat</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $pickupRequest->address }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Kolektor</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $pickupRequest->collector?->name ?? 'Belum ditugaskan' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Diajukan</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $pickupRequest->created_at->format('d M Y H:i') }}</dd>
                    </div>
                    @if ($pickupRequest->scheduled_at)
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Jadwal</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $pickupRequest->scheduled_at->format('d M Y H:i') }}</dd>
                        </div>
                    @endif
                    @if ($pickupRequest->total_weight)
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Total Berat</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $pickupRequest->total_weight }} kg</dd>
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
                            <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Catatan</dt>
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

                @if ($pickupRequest->status === 'pending')
                    <div class="mt-6 rounded-lg bg-slate-50 p-5">
                        <h3 class="mb-3 text-sm font-bold text-slate-700">Proses Pengajuan</h3>
                        <form method="POST" action="{{ route('admin.pickup-requests.approve', $pickupRequest) }}" class="space-y-3">
                            @csrf
                            @method('PATCH')
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                <div>
                                    <label for="collector_id" class="form-label">Tugaskan Kolektor <span class="text-red-500">*</span></label>
                                    <select id="collector_id" name="collector_id" required class="form-select @error('collector_id') input-error @enderror">
                                        <option value="">Pilih kolektor ...</option>
                                        @foreach ($collectors as $collector)
                                            <option value="{{ $collector->id }}" @selected(old('collector_id') == $collector->id)>{{ $collector->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('collector_id')
                                        <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label for="scheduled_at" class="form-label">Jadwal Pengambilan</label>
                                    <input type="datetime-local" id="scheduled_at" name="scheduled_at"
                                           class="form-control @error('scheduled_at') input-error @enderror">
                                    @error('scheduled_at')
                                        <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
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