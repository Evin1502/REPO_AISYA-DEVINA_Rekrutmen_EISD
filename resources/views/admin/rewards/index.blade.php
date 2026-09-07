@extends('layouts.admin')

@section('title', 'Reward - Admin TemJi')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Reward</h1>
            <p class="text-sm text-slate-500">Kelola katalog reward untuk penukaran poin.</p>
        </div>
        <a href="{{ route('admin.rewards.create') }}" class="btn btn-primary">＋ Tambah Reward</a>
    </div>

    @if ($rewards->isEmpty())
        <x-empty-state message="Belum ada reward."
                       :action-route="route('admin.rewards.create')" action-label="Tambah Reward" />
    @else
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($rewards as $reward)
                <div class="card flex flex-col overflow-hidden">
                    @if ($reward->image)
                        <img src="{{ asset('storage/' . $reward->image) }}" alt="{{ $reward->name }}" class="h-40 w-full object-cover">
                    @else
                        <div class="flex h-40 items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200 text-5xl">🎁</div>
                    @endif
                    <div class="flex flex-1 flex-col p-5">
                        <div class="flex items-center justify-between gap-2">
                            <h3 class="text-lg font-bold text-slate-900">{{ $reward->name }}</h3>
                            <span class="shrink-0 rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">
                                {{ $reward->categoryLabel() }}
                            </span>
                        </div>
                        <p class="mt-1 flex-1 text-sm text-slate-500">{{ $reward->description ?? 'Tanpa deskripsi.' }}</p>
                        <div class="mt-3 flex items-center justify-between text-sm">
                            <span class="font-semibold text-admin-600">{{ $reward->points_required }} poin</span>
                            <span class="text-slate-500">
                                @if ($reward->isSaldo())
                                    Nilai: {{ $reward->displayValue() }}
                                @else
                                    Stok: {{ $reward->stock }}
                                @endif
                            </span>
                            <span class="text-xs text-slate-400">{{ $reward->point_exchanges_count }} penukaran</span>
                        </div>
                        <div class="mt-4 flex gap-2">
                            <a href="{{ route('admin.rewards.edit', $reward) }}" class="btn btn-secondary btn-sm flex-1">Edit</a>
                            <form method="POST" action="{{ route('admin.rewards.destroy', $reward) }}" class="flex-1"
                                  onsubmit="return confirm('Hapus reward ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm w-full">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex justify-center">
            {{ $rewards->links() }}
        </div>
    @endif
@endsection