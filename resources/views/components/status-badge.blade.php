@props(['status'])

@php
    $map = [
        'pending' => 'bg-amber-100 text-amber-800',
        'approved' => 'bg-blue-100 text-blue-800',
        'scheduled' => 'bg-indigo-100 text-indigo-800',
        'collected' => 'bg-green-100 text-green-800',
        'rejected' => 'bg-red-100 text-red-800',
        'earn' => 'bg-green-100 text-green-800',
        'redeem' => 'bg-red-100 text-red-800',
        'refund' => 'bg-indigo-100 text-indigo-800',
    ];
    $class = $map[$status] ?? 'bg-slate-100 text-slate-800';
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ' . $class]) }}>
    {{ ucfirst($status) }}
</span>
