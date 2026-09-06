@extends('layouts.admin')

@section('title', 'Edit Berita - Admin TemJi')

@section('content')
    <div class="mx-auto max-w-2xl">
        <a href="{{ route('admin.news.index') }}" class="mb-4 inline-block text-sm font-semibold text-admin-600 hover:underline">&larr; Kembali</a>

        <div class="card">
            <div class="card-header">
                <h1 class="text-lg font-bold text-slate-900">Edit Berita</h1>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.news.update', $news) }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    @if ($news->image)
                        <div>
                            <p class="form-label">Gambar Saat Ini</p>
                            <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}"
                                 class="h-32 w-full rounded-lg object-cover">
                        </div>
                    @endif

                    <div>
                        <label for="title" class="form-label">Judul</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $news->title) }}" required
                               class="form-control @error('title') input-error @enderror">
                        @error('title')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="content" class="form-label">Isi Berita</label>
                        <textarea id="content" name="content" rows="12" required
                                  class="form-control @error('content') input-error @enderror">{{ old('content', $news->content) }}</textarea>
                        @error('content')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="image" class="form-label">Ganti Gambar (opsional)</label>
                        <input type="file" id="image" name="image" accept="image/*"
                               class="form-control @error('image') input-error @enderror">
                        @error('image')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection