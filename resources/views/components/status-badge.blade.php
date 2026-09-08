@props(['status', 'label' => null])

@php
    $map = [
        'pending' => ['bg' => 'bg-amber-100 text-amber-800', 'dot' => 'bg-amber-500'],
        'approved' => ['bg' => 'bg-blue-100 text-blue-800', 'dot' => 'bg-blue-500'],
        'scheduled' => ['bg' => 'bg-indigo-100 text-indigo-800', 'dot' => 'bg-indigo-500'],
        'collected' => ['bg' => 'bg-green-100 text-green-800', 'dot' => 'bg-green-500'],
        'rejected' => ['bg' => 'bg-red-100 text-red-800', 'dot' => 'bg-red-500'],
        'earn' => ['bg' => 'bg-green-100 text-green-800', 'dot' => 'bg-green-500'],
        'redeem' => ['bg' => 'bg-red-100 text-red-800', 'dot' => 'bg-red-500'],
        'refund' => ['bg' => 'bg-indigo-100 text-indigo-800', 'dot' => 'bg-indigo-500'],
    ];
    $style = $map[$status] ?? ['bg' => 'bg-surface-container text-on-surface-variant', 'dot' => 'bg-outline'];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold shadow-sm ring-1 ring-inset ring-black/5 ' . $style['bg']]) }}>
    <span class="h-1.5 w-1.5 rounded-full {{ $style['dot'] }} {{ $status === 'pending' ? 'animate-pulse' : '' }}" aria-hidden="true"></span>
    {{ $label ?? ucfirst($status) }}
</span>
