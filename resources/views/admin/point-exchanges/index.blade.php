@extends('layouts.admin')

@section('title', 'Penukaran Poin - Admin TemJi')

@section('content')
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-on-surface">Penukaran Poin</h1>
            <p class="text-sm text-on-surface-variant">Proses penukaran poin menjadi reward.</p>
        </div>
        <form method="GET" action="{{ route('admin.point-exchanges.index') }}" class="flex items-center gap-2">
            <select name="status" class="form-select w-auto" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                @foreach (['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'] as $value => $label)
                    <option value="{{ $value }}" @selected($currentStatus === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </form>
    </div>

    @if ($pointExchanges->isEmpty())
        <x-empty-state message="Tidak ada penukaran poin dengan filter ini." />
    @else
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-surface-container-low/80">
                        <tr>
                            <th>#</th>
                            <th>Resident</th>
                            <th>Reward</th>
                            <th>Jenis</th>
                            <th>Nilai</th>
                            <th>Poin Dipakai</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pointExchanges as $exchange)
                            <tr>
                                <td>{{ $exchange->id }}</td>
                                <td class="font-medium">{{ $exchange->user?->name ?? '-' }}</td>
                                <td>{{ $exchange->reward_name ?? $exchange->reward?->name ?? '-' }}</td>
                                <td><span class="text-xs">{{ $exchange->typeLabel() }}</span></td>
                                <td>{{ $exchange->displayValue() }}</td>
                                <td>{{ number_format($exchange->points_used) }}</td>
                                <td>{{ $exchange->created_at->format('d M Y H:i') }}</td>
                                <td><x-status-badge :status="$exchange->status" /></td>
                                <td class="text-right">
                                    @if ($exchange->status === 'pending')
                                        <div class="flex justify-end gap-2">
                                            <form method="POST" action="{{ route('admin.point-exchanges.approve', $exchange) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-primary btn-sm">Setujui</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.point-exchanges.reject', $exchange) }}"
                                                  onsubmit="return confirm('Tolak penukaran ini? Poin akan dikembalikan.')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-xs text-outline">—</span>
                                    @endif
                                </td>
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