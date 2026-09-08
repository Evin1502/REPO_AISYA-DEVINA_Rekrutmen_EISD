@extends('layouts.admin')

@section('title', 'Edit Reward - Admin TemJi')

@section('content')
    <div class="mx-auto max-w-xl">
        <a href="{{ route('admin.rewards.index') }}" class="mb-4 inline-block text-sm font-semibold text-admin-600 hover:underline">&larr; Kembali</a>

        <div class="card">
            <div class="card-header">
                <h1 class="text-lg font-bold text-on-surface">Edit Reward</h1>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.rewards.update', $reward) }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    @if ($reward->image)
                        <div>
                            <p class="form-label">Gambar Saat Ini</p>
                            <img src="{{ asset('storage/' . $reward->image) }}" alt="{{ $reward->name }}"
                                 class="h-32 w-full rounded-lg object-cover">
                        </div>
                    @endif

                    <div>
                        <label for="name" class="form-label">Nama Reward</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $reward->name) }}" required
                               class="form-control @error('name') input-error @enderror">
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="category" class="form-label">Jenis Reward</label>
                        <select id="category" name="category" required
                                class="form-select @error('category') input-error @enderror">
                            <option value="saldo" @selected(old('category', $reward->category) === 'saldo')>Saldo / Cash Balance</option>
                            <option value="barang" @selected(old('category', $reward->category) === 'barang')>Barang Fisik</option>
                        </select>
                        @error('category')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="nominalField">
                        <label for="nominal" class="form-label">Nominal Saldo (Rp)</label>
                        <input type="number" id="nominal" name="nominal" min="0" step="1" value="{{ old('nominal', $reward->nominal) }}"
                               class="form-control @error('nominal') input-error @enderror">
                        @error('nominal')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Dipilih pada jenjang Rp5.000 s.d. Rp100.000.</div>
                    </div>

                    <div>
                        <label for="points_required" class="form-label">Poin yang Dibutuhkan</label>
                        <input type="number" id="points_required" name="points_required" min="1" value="{{ old('points_required', $reward->points_required) }}" required
                               class="form-control @error('points_required') input-error @enderror">
                        @error('points_required')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="stock" class="form-label">Stok</label>
                        <input type="number" id="stock" name="stock" min="0" value="{{ old('stock', $reward->stock) }}" required
                               class="form-control @error('stock') input-error @enderror">
                        @error('stock')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Saldo bersifat virtual, isikan angka besar (mis. 999999).</div>
                    </div>

                    <div>
                        <label for="image" class="form-label">Ganti Gambar (opsional)</label>
                        <input type="file" id="image" name="image" accept="image/*"
                               class="form-control @error('image') input-error @enderror">
                        @error('image')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="form-label">Deskripsi (opsional)</label>
                        <textarea id="description" name="description" rows="3" maxlength="1000"
                                  class="form-control @error('description') input-error @enderror">{{ old('description', $reward->description) }}</textarea>
                        @error('description')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ route('admin.rewards.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const category = document.getElementById('category');
            const nominalField = document.getElementById('nominalField');
            const nominal = document.getElementById('nominal');

            function toggleNominal() {
                const isSaldo = category.value === 'saldo';
                nominalField.classList.toggle('hidden', !isSaldo);
                nominal.required = isSaldo;
            }

            category.addEventListener('change', toggleNominal);
            toggleNominal();
        </script>
    @endpush
@endsection