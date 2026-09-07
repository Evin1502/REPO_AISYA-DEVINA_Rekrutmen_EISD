@extends('layouts.app')

@section('title', 'Riwayat Penukaran - TemJi')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Riwayat Penukaran Poin</h1>
            <p class="text-sm text-slate-500">Riwayat penukaran reward Anda.</p>
        </div>
        <a href="{{ route('resident.rewards.index') }}" class="btn btn-primary">Lihat Katalog Reward</a>
    </div>

    @if ($pointExchanges->isEmpty())
        <x-empty-state message="Belum ada penukaran poin."
                       :action-route="route('resident.rewards.index')" action-label="Lihat Katalog" />
    @else
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th>#</th>
                            <th>Reward</th>
                            <th>Jenis</th>
                            <th>Nilai</th>
                            <th>Poin Dipakai</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pointExchanges as $exchange)
                            <tr>
                                <td>{{ $pointExchanges->firstItem() + $loop->index }}</td>
                                <td class="font-medium">{{ $exchange->reward_name ?? $exchange->reward?->name }}</td>
                                <td><span class="text-xs">{{ $exchange->typeLabel() }}</span></td>
                                <td>{{ $exchange->displayValue() }}</td>
                                <td>{{ number_format($exchange->points_used) }}</td>
                                <td>{{ $exchange->created_at->format('d M Y H:i') }}</td>
                                <td><x-status-badge :status="$exchange->status" /></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 flex justify-center">
            {{ $pointExchanges->links() }}
        </div>
    @endif
@endsection