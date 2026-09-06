@extends('layouts.admin')

@section('title', 'Edit Kategori - Admin TemJi')

@section('content')
    <div class="mx-auto max-w-xl">
        <a href="{{ route('admin.waste-categories.index') }}" class="mb-4 inline-block text-sm font-semibold text-admin-600 hover:underline">&larr; Kembali</a>

        <div class="card">
            <div class="card-header">
                <h1 class="text-lg font-bold text-slate-900">Edit Kategori Sampah</h1>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.waste-categories.update', $wasteCategory) }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="form-label">Nama Kategori</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $wasteCategory->name) }}" required
                               class="form-control @error('name') input-error @enderror">
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="points_per_kg" class="form-label">Poin per Kilogram</label>
                        <input type="number" id="points_per_kg" name="points_per_kg" min="1" value="{{ old('points_per_kg', $wasteCategory->points_per_kg) }}" required
                               class="form-control @error('points_per_kg') input-error @enderror">
                        @error('points_per_kg')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="form-label">Deskripsi (opsional)</label>
                        <textarea id="description" name="description" rows="3" maxlength="1000"
                                  class="form-control @error('description') input-error @enderror">{{ old('description', $wasteCategory->description) }}</textarea>
                        @error('description')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ route('admin.waste-categories.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection