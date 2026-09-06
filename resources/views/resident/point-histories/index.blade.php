@extends('layouts.app')

@section('title', 'Riwayat Poin - TemJi')

@section('content')
    <div class="mb-4">
        <h1 class="text-xl font-bold text-slate-900">Riwayat Poin</h1>
        <p class="text-sm text-slate-500">Semua transaksi poin Anda di TemJi.</p>
    </div>

    @if ($pointHistories->isEmpty())
        <x-empty-state message="Belum ada riwayat poin."
                       :action-route="route('resident.pickup-requests.create')" action-label="Ajukan Penjemputan" />
    @else
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th>#</th>
                            <th>Deskripsi</th>
                            <th>Tipe</th>
                            <th class="text-right">Poin</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pointHistories as $history)
                            <tr>
                                <td>{{ $pointHistories->firstItem() + $loop->index }}</td>
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
        </div>

        <div class="mt-4 flex justify-center">
            {{ $pointHistories->links() }}
        </div>
    @endif
@endsection