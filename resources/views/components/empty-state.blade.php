@props(['message' => 'Belum ada data.', 'actionRoute' => null, 'actionLabel' => 'Buat Baru'])

<div {{ $attributes->merge(['class' => 'card py-14']) }}>
    <div class="card-body flex flex-col items-center gap-3 text-center">
        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-50 to-surface-container text-3xl shadow-sm ring-1 ring-outline-variant/30">🗂️</div>
        <p class="max-w-xs text-sm text-on-surface-variant">{{ $message }}</p>
        @if ($actionRoute)
            <a href="{{ $actionRoute }}" class="btn btn-primary btn-pill mt-1">{{ $actionLabel }}</a>
        @endif
    </div>
</div>
