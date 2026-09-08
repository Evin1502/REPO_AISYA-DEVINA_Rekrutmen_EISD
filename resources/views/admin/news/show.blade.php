@extends('layouts.admin')

@section('title', $news->title . ' - Admin TemJi')

@section('content')
    <div class="mx-auto max-w-2xl">
        <div class="mb-4 flex items-center justify-between">
            <a href="{{ route('admin.news.index') }}" class="text-sm font-semibold text-admin-600 hover:underline">&larr; Kembali</a>
            <div class="flex gap-2">
                <a href="{{ route('admin.news.edit', $news) }}" class="btn btn-secondary btn-sm">Edit</a>
                <form method="POST" action="{{ route('admin.news.destroy', $news) }}"
                      onsubmit="return confirm('Hapus berita ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                </form>
            </div>
        </div>

        <article class="card overflow-hidden">
            @if ($news->image)
                <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}" class="h-64 w-full object-cover">
            @endif
            <div class="card-body p-6">
                <p class="mb-2 text-xs text-on-surface-variant">
                    {{ $news->author?->name ?? 'Admin' }} &middot; {{ $news->created_at->format('d M Y H:i') }}
                </p>
                <h1 class="mb-4 text-2xl font-bold text-on-surface">{{ $news->title }}</h1>
                <div class="whitespace-pre-wrap text-sm leading-relaxed text-on-surface">
                    {!! nl2br(e($news->content)) !!}
                </div>
                <a href="{{ route('resident.news.show', $news) }}" target="_blank"
                   class="mt-6 inline-block text-sm font-semibold text-admin-600 hover:underline">
                    Lihat sebagai Resident →
                </a>
            </div>
        </article>
    </div>
@endsection