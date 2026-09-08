@extends('layouts.app')

@section('title', 'Katalog Reward - TemJi')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-on-surface">Katalog Reward</h1>
            <p class="text-sm text-on-surface-variant">Pilih jenis reward yang ingin kamu tukar.</p>
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

    @if ($saldoCount === 0 && $barangCount === 0)
        <x-empty-state message="Belum ada reward yang tersedia." />
    @else
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <a href="{{ route('resident.rewards.saldo') }}"
               class="card group flex flex-col overflow-hidden transition hover:ring-2 hover:ring-green-300">
                <div class="flex h-40 items-center justify-center bg-gradient-to-br from-green-50 to-green-100 text-5xl">💰</div>
                <div class="flex flex-1 flex-col p-5">
                    <h3 class="text-lg font-bold text-on-surface">Saldo / Cash Balance</h3>
                    <p class="mt-1 flex-1 text-sm text-on-surface-variant">
                        Tukarkan poin menjadi saldo cash senilai Rp5.000 s.d. Rp100.000. Saldo dicatat & dikreditkan
                        setelah disetujui Admin.
                    </p>
                    <div class="mt-4 flex items-center justify-between">
                        @if ($saldoCount)
                            <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-3 py-1 text-sm font-bold text-green-700">
                                {{ $saldoCount }} pilihan
                            </span>
                        @else
                            <span class="text-xs text-outline">Belum tersedia</span>
                        @endif
                        <span class="text-sm font-semibold text-green-600 group-hover:underline">Tukar Saldo &rarr;</span>
                    </div>
                </div>
            </a>

            <a href="{{ route('resident.rewards.barang') }}"
               class="card group flex flex-col overflow-hidden transition hover:ring-2 hover:ring-brand-300">
                <div class="flex h-40 items-center justify-center bg-gradient-to-br from-brand-50 to-brand-100 text-5xl">🎁</div>
                <div class="flex flex-1 flex-col p-5">
                    <h3 class="text-lg font-bold text-on-surface">Barang</h3>
                    <p class="mt-1 flex-1 text-sm text-on-surface-variant">
                        Tukarkan poin menjadi barang fisik pilihan (stok terbatas) dan jemput/terima langsung dari TemJi.
                    </p>
                    <div class="mt-4 flex items-center justify-between">
                        @if ($barangCount)
                            <span class="inline-flex items-center gap-1 rounded-full bg-brand-50 px-3 py-1 text-sm font-bold text-brand-700">
                                {{ $barangCount }} pilihan
                            </span>
                        @else
                            <span class="text-xs text-outline">Belum tersedia</span>
                        @endif
                        <span class="text-sm font-semibold text-brand-600 group-hover:underline">Tukar Barang &rarr;</span>
                    </div>
                </div>
            </a>
        </div>
    @endif

    <div class="mt-8 text-center">
        <a href="{{ route('resident.point-exchanges.index') }}" class="text-sm font-semibold text-brand-600 hover:underline">
            Lihat Riwayat Penukaran &rarr;
        </a>
    </div>
@endsection