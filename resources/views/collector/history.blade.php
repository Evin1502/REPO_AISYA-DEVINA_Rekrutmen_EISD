@extends('layouts.collector')

@section('title', 'Riwayat Penjemputan - Kolektor TemJi')

@section('content')
    <div class="mb-4">
        <h1 class="text-xl font-bold text-slate-900">Riwayat Penjemputan</h1>
        <p class="text-sm text-slate-500">Penjemputan yang sudah Anda selesaikan.</p>
    </div>

    @if ($pickupRequests->isEmpty())
        <x-empty-state message="Belum ada riwayat penjemputan selesai." />
    @else
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th>#</th>
                            <th>Resident</th>
                            <th>Alamat</th>
                            <th>Total Berat</th>
                            <th>Total Poin</th>
                            <th>Selesai Pada</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pickupRequests as $pickupRequest)
                            <tr>
                                <td>{{ $pickupRequest->id }}</td>
                                <td class="font-medium">{{ $pickupRequest->resident?->name ?? '-' }}</td>
                                <td class="max-w-xs">{{ $pickupRequest->address }}</td>
                                <td>{{ $pickupRequest->total_weight }} kg</td>
                                <td class="font-bold text-green-600">+{{ number_format($pickupRequest->total_points) }}</td>
                                <td>{{ $pickupRequest->updated_at->format('d M Y H:i') }}</td>
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