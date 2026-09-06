@extends('layouts.admin')

@section('title', 'Pengajuan Sampah - Admin TemJi')

@section('content')
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Pengajuan Sampah</h1>
            <p class="text-sm text-slate-500">Kelola dan proses pengajuan penjemputan.</p>
        </div>
        <form method="GET" action="{{ route('admin.pickup-requests.index') }}" class="flex items-center gap-2">
            <select name="status" class="form-select w-auto" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                @foreach (['pending' => 'Menunggu', 'approved' => 'Disetujui', 'scheduled' => 'Dijadwalkan', 'collected' => 'Selesai', 'rejected' => 'Ditolak'] as $value => $label)
                    <option value="{{ $value }}" @selected($currentStatus === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </form>
    </div>

    @if ($pickupRequests->isEmpty())
        <x-empty-state message="Tidak ada pengajuan dengan filter ini." />
    @else
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th>#</th>
                            <th>Resident</th>
                            <th>Alamat</th>
                            <th>Kolektor</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pickupRequests as $pickupRequest)
                            <tr>
                                <td>{{ $pickupRequest->id }}</td>
                                <td class="font-medium">{{ $pickupRequest->resident?->name ?? '-' }}</td>
                                <td class="max-w-xs">{{ $pickupRequest->address }}</td>
                                <td>{{ $pickupRequest->collector?->name ?? '-' }}</td>
                                <td>{{ $pickupRequest->created_at->format('d M Y H:i') }}</td>
                                <td><x-status-badge :status="$pickupRequest->status" /></td>
                                <td class="text-right">
                                    <a href="{{ route('admin.pickup-requests.show', $pickupRequest) }}" class="btn btn-secondary btn-sm">Detail</a>
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