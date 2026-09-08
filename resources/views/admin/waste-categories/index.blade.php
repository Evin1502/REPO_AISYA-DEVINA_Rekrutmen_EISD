@extends('layouts.admin')

@section('title', 'Kategori Sampah - Admin TemJi')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-on-surface">Kategori Sampah</h1>
            <p class="text-sm text-on-surface-variant">Kelola kategori sampah dan poin per kilogram.</p>
        </div>
        <a href="{{ route('admin.waste-categories.create') }}" class="btn btn-primary">＋ Tambah Kategori</a>
    </div>

    @if ($categories->isEmpty())
        <x-empty-state message="Belum ada kategori sampah."
                       :action-route="route('admin.waste-categories.create')" action-label="Tambah Kategori" />
    @else
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-surface-container-low/80">
                        <tr>
                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th>Poin / kg</th>
                            <th>Dipakai Pada</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td class="font-medium">{{ $category->name }}</td>
                                <td class="max-w-md">{{ $category->description ?? '-' }}</td>
                                <td><span class="inline-flex rounded-full bg-brand-50 px-2.5 py-0.5 text-xs font-semibold text-brand-700">{{ $category->points_per_kg }} poin</span></td>
                                <td>{{ $category->pickup_requests_count }} pengajuan</td>
                                <td class="text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.waste-categories.edit', $category) }}" class="btn btn-secondary btn-sm">Edit</a>
                                        <form method="POST" action="{{ route('admin.waste-categories.destroy', $category) }}"
                                              onsubmit="return confirm('Hapus kategori ini?')">
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
            {{ $categories->links() }}
        </div>
    @endif
@endsection