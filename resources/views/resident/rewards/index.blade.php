@extends('layouts.app')

@section('title', 'Katalog Reward - TemJi')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Katalog Reward</h1>
            <p class="text-sm text-slate-500">Tukarkan poinmu dengan reward berikut.</p>
        </div>
        <span class="inline-flex items-center gap-2 rounded-full bg-brand-50 px-4 py-2 text-sm font-semibold text-brand-700">
            ⭐ {{ number_format($user->points) }} poin
        </span>
    </div>

    @if ($rewards->isEmpty())
        <x-empty-state message="Belum ada reward yang tersedia." />
    @else
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($rewards as $reward)
                <div class="card flex flex-col overflow-hidden">
                    @if ($reward->image)
                        <img src="{{ asset('storage/' . $reward->image) }}" alt="{{ $reward->name }}"
                             class="h-40 w-full object-cover">
                    @else
                        <div class="flex h-40 items-center justify-center bg-gradient-to-br from-brand-50 to-brand-100 text-5xl">🎁</div>
                    @endif
                    <div class="flex flex-1 flex-col p-5">
                        <h3 class="text-lg font-bold text-slate-900">{{ $reward->name }}</h3>
                        <p class="mt-1 flex-1 text-sm text-slate-500">{{ $reward->description }}</p>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="inline-flex items-center gap-1 rounded-full bg-brand-50 px-3 py-1 text-sm font-bold text-brand-700">
                                {{ $reward->points_required }} poin
                            </span>
                            <span class="text-xs text-slate-500">Stok: {{ $reward->stock }}</span>
                        </div>
                        <form method="POST" action="{{ route('resident.rewards.exchange', $reward) }}" class="mt-4"
                              onsubmit="return confirm('Tukar reward ini dengan {{ $reward->points_required }} poin?')">
                            @csrf
                            <button type="submit"
                                    class="btn btn-primary w-full"
                                    @if ($user->points < $reward->points_required || $reward->stock < 1) disabled @endif>
                                @if ($user->points < $reward->points_required)
                                    Poin Kurang
                                @elseif ($reward->stock < 1)
                                    Stok Habis
                                @else
                                    Tukar Sekarang
                                @endif
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex justify-center">
            {{ $rewards->links() }}
        </div>
    @endif
@endsection