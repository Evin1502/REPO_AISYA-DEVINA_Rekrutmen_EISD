@props(['status'])

@php
    $steps = [
        'pending' => 'Diajukan',
        'approved' => 'Disetujui',
        'scheduled' => 'Dijadwalkan',
        'collected' => 'Selesai',
    ];
    $order = array_keys($steps);
    $currentIndex = array_search($status, $order, true);
    $isRejected = $status === 'rejected';
@endphp

@if ($isRejected)
    <div class="flex items-center gap-2 rounded-lg bg-red-50 px-4 py-3 text-sm font-semibold text-red-700 ring-1 ring-red-200">
        <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
        </svg>
        Pengajuan ini ditolak oleh Admin.
    </div>
@else
    <ol class="flex w-full items-start" aria-label="Status progres pengajuan">
        @foreach ($steps as $key => $label)
            @php
                $index = array_search($key, $order, true);
                $isDone = $currentIndex !== false && $index < $currentIndex;
                $isActive = $key === $status;
                $isUpcoming = ! $isDone && ! $isActive;
            @endphp
            <li class="flex {{ ! $loop->last ? 'w-full' : '' }} flex-col items-center">
                <div class="flex w-full items-center">
                    <span
                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-xs font-bold ring-2
                        {{ $isDone ? 'bg-brand-600 text-white ring-brand-600' : '' }}
                        {{ $isActive ? 'bg-brand-100 text-brand-700 ring-brand-500' : '' }}
                        {{ $isUpcoming ? 'bg-slate-100 text-slate-400 ring-slate-200' : '' }}"
                        aria-current="{{ $isActive ? 'step' : 'false' }}"
                    >
                        @if ($isDone)
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                        @else
                            {{ $index + 1 }}
                        @endif
                    </span>
                    @if (! $loop->last)
                        <span class="mx-2 h-0.5 w-full {{ $isDone ? 'bg-brand-600' : 'bg-slate-200' }}" aria-hidden="true"></span>
                    @endif
                </div>
                <span class="mt-2 text-center text-xs font-medium {{ $isActive || $isDone ? 'text-slate-800' : 'text-slate-400' }}">
                    {{ $label }}
                </span>
            </li>
        @endforeach
    </ol>
@endif
