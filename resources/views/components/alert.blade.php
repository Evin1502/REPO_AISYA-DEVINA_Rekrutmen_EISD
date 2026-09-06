@props(['type' => 'success'])

@php
    $styles = [
        'success' => 'bg-green-50 text-green-800 ring-green-200',
        'error' => 'bg-red-50 text-red-800 ring-red-200',
        'info' => 'bg-blue-50 text-blue-800 ring-blue-200',
    ][$type] ?? 'bg-green-50 text-green-800 ring-green-200';
    $icons = [
        'success' => '✓',
        'error' => '✕',
        'info' => 'ℹ',
    ][$type] ?? '✓';
@endphp

<div {{ $attributes->merge(['class' => 'mb-4 flex items-start gap-2 rounded-lg px-4 py-3 text-sm ring-1 ' . $styles]) }} role="alert">
    <span class="mt-0.5 font-bold">{{ $icons }}</span>
    <div>{{ $slot }}</div>
</div>
