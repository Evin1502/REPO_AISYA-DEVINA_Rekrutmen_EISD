@props(['name' => null])

@php
    $initialsSource = $name ?? auth()->user()?->name ?? 'T';
    $parts = preg_split('/\s+/', trim($initialsSource)) ?: [];
    $initials = strtoupper(mb_substr($parts[0] ?? 'T', 0, 1).(isset($parts[1]) ? mb_substr($parts[1], 0, 1) : ''));
@endphp
<span {{ $attributes->merge(['class' => 'inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-secondary-container text-xs font-bold text-on-secondary-container']) }}>{{ $initials }}</span>
