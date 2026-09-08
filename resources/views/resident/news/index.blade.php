@extends('layouts.app')

@section('title', 'Berita - TemJi')

@section('content')
    <div class="mb-4">
        <h1 class="text-xl font-bold text-on-surface">Berita Terbaru</h1>
        <p class="text-sm text-on-surface-variant">Informasi terbaru seputar TemJi dan pengelolaan sampah.</p>
    </div>

    @if ($newsList->isEmpty())
        <x-empty-state message="Belum ada berita." />
    @else
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($newsList as $news)
                <article class="card flex flex-col overflow-hidden">
                    @if ($news->image)
                        <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}" class="h-40 w-full object-cover">
                    @else
                        <div class="flex h-40 items-center justify-center bg-gradient-to-br from-surface-container-low to-surface-container-high text-4xl">📰</div>
                    @endif
                    <div class="flex flex-1 flex-col p-5">
                        <h3 class="text-base font-bold leading-snug text-on-surface">
                            <a href="{{ route('resident.news.show', $news) }}" class="hover:underline">{{ $news->title }}</a>
                        </h3>
                        <p class="mt-2 flex-1 text-sm text-on-surface-variant">
                            {{ \Illuminate\Support\Str::limit(strip_tags($news->content), 120) }}
                        </p>
                        <div class="mt-4 flex items-center justify-between">
                            <small class="text-xs text-on-surface-variant">
                                {{ $news->author?->name ?? 'Admin' }} &middot; {{ $news->created_at->format('d M Y') }}
                            </small>
                            <a href="{{ route('resident.news.show', $news) }}" class="btn btn-secondary btn-sm">Baca</a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-6 flex justify-center">
            {{ $newsList->links() }}
        </div>
    @endif
@endsection