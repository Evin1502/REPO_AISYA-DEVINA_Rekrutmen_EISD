@extends('layouts.admin')

@section('title', 'Berita - Admin TemJi')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Berita</h1>
            <p class="text-sm text-slate-500">Kelola artikel berita untuk resident.</p>
        </div>
        <a href="{{ route('admin.news.create') }}" class="btn btn-primary">＋ Tulis Berita</a>
    </div>

    @if ($newsList->isEmpty())
        <x-empty-state message="Belum ada berita."
                       :action-route="route('admin.news.create')" action-label="Tulis Berita" />
    @else
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Tanggal</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($newsList as $news)
                            <tr>
                                <td class="font-medium">{{ $news->title }}</td>
                                <td>{{ $news->author?->name ?? '-' }}</td>
                                <td>{{ $news->created_at->format('d M Y H:i') }}</td>
                                <td class="text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.news.show', $news) }}" class="btn btn-secondary btn-sm">Lihat</a>
                                        <a href="{{ route('admin.news.edit', $news) }}" class="btn btn-secondary btn-sm">Edit</a>
                                        <form method="POST" action="{{ route('admin.news.destroy', $news) }}"
                                              onsubmit="return confirm('Hapus berita ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 flex justify-center">
            {{ $newsList->links() }}
        </div>
    @endif
@endsection