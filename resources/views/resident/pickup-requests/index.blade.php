@extends('layouts.app')

@section('title', 'Pengajuan Pengambilan - TemJi')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-on-surface">Pengajuan Pengambilan Sampah</h1>
            <p class="text-sm text-on-surface-variant">Kelola pengajuan penjemputan sampah Anda.</p>
        </div>
        <a href="{{ route('resident.pickup-requests.create') }}" class="btn btn-primary">＋ Ajukan Baru</a>
    </div>

    @if ($pickupRequests->isEmpty())
        <x-empty-state message="Belum ada pengajuan. Klik tombol Ajukan Baru untuk mulai."
                       :action-route="route('resident.pickup-requests.create')" action-label="Ajukan Baru" />
    @else
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-surface-container-low/80">
                        <tr>
                            <th>#</th>
                            <th>Alamat</th>
                            <th>Kategori</th>
                            <th>Tanggal Penjemputan</th>
                            <th>Slot Waktu</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pickupRequests as $pickupRequest)
                            <tr>
                                <td>{{ $pickupRequests->firstItem() + $loop->index }}</td>
                                <td class="max-w-xs">{{ $pickupRequest->address }}</td>
                                <td>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($pickupRequest->wasteCategories as $category)
                                            <span class="inline-flex rounded-full bg-brand-50 px-2 py-0.5 text-xs font-medium text-brand-700">{{ $category->name }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>{{ $pickupRequest->scheduled_at ? $pickupRequest->scheduled_at->format('d M Y') : $pickupRequest->created_at->format('d M Y') }}</td>
                                <td>{{ $pickupRequest->timeSlotLabel() ?? '–' }}</td>
                                <td><x-status-badge :status="$pickupRequest->status" :label="$pickupRequest->statusLabel()" /></td>
                                <td class="text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('resident.pickup-requests.show', $pickupRequest) }}" class="btn btn-secondary btn-sm">Detail</a>
                                        @if ($pickupRequest->status === 'pending')
                                            <form method="POST" action="{{ route('resident.pickup-requests.destroy', $pickupRequest) }}"
                                                  onsubmit="return confirm('Batalkan pengajuan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Batalkan</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 flex justify-center">
            {{ $pickupRequests->links() }}
        </div>
    @endif
@endsection