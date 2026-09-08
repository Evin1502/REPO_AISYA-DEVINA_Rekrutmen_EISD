@props(['message' => 'Belum ada data.', 'actionRoute' => null, 'actionLabel' => 'Buat Baru'])

<div {{ $attributes->merge(['class' => 'card py-12']) }}>
    <div class="card-body flex flex-col items-center gap-3 text-center">
        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-surface-container text-3xl">🗂️</div>
        <p class="text-sm text-on-surface-variant">{{ $message }}</p>
        @if ($actionRoute)
            <a href="{{ $actionRoute }}" class="btn btn-primary">{{ $actionLabel }}</a>
        @endif
    </div>
</div>
