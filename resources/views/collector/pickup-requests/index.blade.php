@extends('layouts.collector')

@section('title', 'Antrean Penjemputan - Kolektor TemJi')

@section('content')
    <div class="mb-4">
        <h1 class="text-xl font-bold text-slate-900">Antrean Penjemputan</h1>
        <p class="text-sm text-slate-500">Pengajuan yang ditugaskan kepada Anda.</p>
    </div>

    @if ($pickupRequests->isEmpty())
        <x-empty-state message="Tidak ada penjemputan yang ditugaskan untuk saat ini." />
    @else
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th>#</th>
                            <th>Resident</th>
                            <th>Alamat</th>
                            <th>Kategori</th>
                            <th>Jadwal</th>
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
                                <td>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($pickupRequest->wasteCategories as $category)
                                            <span class="inline-flex rounded-full bg-collector-50 px-2 py-0.5 text-xs font-medium text-collector-700">{{ $category->name }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>{{ $pickupRequest->scheduled_at?->format('d M Y H:i') ?? '-' }}</td>
                                <td><x-status-badge :status="$pickupRequest->status" /></td>
                                <td class="text-right">
                                    <a href="{{ route('collector.pickup-requests.show', $pickupRequest) }}" class="btn btn-secondary btn-sm">Detail</a>
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