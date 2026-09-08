@extends('layouts.app')

@section('title', $news->title . ' - TemJi')

@section('content')
    <div class="mx-auto max-w-3xl">
        <a href="{{ route('resident.news.index') }}" class="mb-4 inline-block text-sm font-semibold text-brand-600 hover:underline">&larr; Kembali ke Berita</a>

        <article class="card overflow-hidden">
            @if ($news->image)
                <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}" class="h-64 w-full object-cover">
            @endif
            <div class="card-body p-6 sm:p-8">
                <p class="mb-2 text-xs text-on-surface-variant">
                    {{ $news->author?->name ?? 'Admin' }} &middot; {{ $news->created_at->format('d M Y H:i') }}
                </p>
                <h1 class="mb-4 text-2xl font-bold text-on-surface">{{ $news->title }}</h1>
                <div class="whitespace-pre-wrap text-sm leading-relaxed text-on-surface">
                    {!! nl2br(e($news->content)) !!}
                </div>
            </div>
        </article>
    </div>
@endsection