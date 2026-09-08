@php
    $isSaldo = ($category ?? null) === \App\Models\Reward::CATEGORY_SALDO;
    $title = $isSaldo ? 'Tukar Saldo / Cash Balance' : 'Tukar Barang';
    $description = $isSaldo
        ? 'Tukarkan poinmu menjadi saldo cash (Rp5.000 s.d. Rp100.000).'
        : 'Tukarkan poinmu menjadi barang fisik pilihan.';
    $icon = $isSaldo ? '💰' : '🎁';
@endphp

@extends('layouts.app')

@section('title', $title . ' - TemJi')

@section('content')
    <div class="mb-6">
        <a href="{{ route('resident.rewards.index') }}" class="mb-4 inline-block text-sm font-semibold text-brand-600 hover:underline">&larr; Kembali ke Katalog Reward</a>
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="flex items-center gap-2 text-xl font-bold text-on-surface"><span>{{ $icon }}</span> {{ $title }}</h1>
                <p class="text-sm text-on-surface-variant">{{ $description }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-2 rounded-full bg-brand-50 px-4 py-2 text-sm font-semibold text-brand-700">
                    ⭐ {{ number_format($user->points) }} poin
                </span>
                <span class="inline-flex items-center gap-2 rounded-full bg-green-50 px-4 py-2 text-sm font-semibold text-green-700">
                    💰 {{ $user->cashBalanceLabel() }}
                </span>
            </div>
        </div>
        <div class="mt-3">
            <a href="{{ $isSaldo ? route('resident.rewards.barang') : route('resident.rewards.saldo') }}"
               class="text-sm font-semibold text-brand-600 hover:underline">
                {{ $isSaldo ? 'Tukar Barang' : 'Tukar Saldo / Cash Balance' }} &rarr;
            </a>
        </div>
    </div>

    @if ($rewards->isEmpty())
        <x-empty-state :message="$isSaldo ? 'Belum ada pilihan saldo yang tersedia.' : 'Belum ada barang yang tersedia.'" />
    @else
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($rewards as $reward)
                <div class="card flex flex-col overflow-hidden">
                    @if ($reward->image)
                        <img src="{{ asset('storage/' . $reward->image) }}" alt="{{ $reward->name }}"
                             class="h-40 w-full object-cover">
                    @elseif ($isSaldo)
                        <div class="flex h-40 items-center justify-center bg-gradient-to-br from-green-50 to-green-100 text-5xl">💰</div>
                    @else
                        <div class="flex h-40 items-center justify-center bg-gradient-to-br from-brand-50 to-brand-100 text-5xl">🎁</div>
                    @endif
                    <div class="flex flex-1 flex-col p-5">
                        <h3 class="text-lg font-bold text-on-surface">{{ $reward->name }}</h3>
                        <p class="mt-1 flex-1 text-sm text-on-surface-variant">{{ $reward->description }}</p>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="inline-flex items-center gap-1 rounded-full bg-brand-50 px-3 py-1 text-sm font-bold text-brand-700">
                                {{ $reward->points_required }} poin
                            </span>
                            <span class="text-xs text-on-surface-variant">
                                @if ($isSaldo)
                                    Nilai: <b>{{ $reward->displayValue() }}</b>
                                @else
                                    Stok: {{ $reward->stock }}
                                @endif
                            </span>
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
            @if ($rewards instanceof \Illuminate\Pagination\AbstractPaginator)
                {{ $rewards->links() }}
            @endif
        </div>
    @endif
@endsection