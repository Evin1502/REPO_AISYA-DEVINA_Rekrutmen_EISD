@extends('layouts.app')

@section('title', 'Ajukan Pengambilan - TemJi')

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="card">
            <div class="card-header">
                <h1 class="text-lg font-bold text-slate-900">Ajukan Pengambilan Sampah</h1>
                <p class="text-sm text-slate-500">Pilih kategori sampah dan perkiraan beratnya. Poin dihitung dari berat riil saat penjemputan.</p>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('resident.pickup-requests.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="address" class="form-label">Alamat Penjemputan</label>
                        <input type="text" id="address" name="address" value="{{ old('address', auth()->user()->address) }}" required
                               class="form-control @error('address') input-error @enderror">
                        @error('address')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label">Kategori Sampah & Perkiraan Berat (kg)</label>
                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                            @foreach ($wasteCategories as $category)
                                <div class="rounded-lg border border-slate-200 p-3">
                                    <div class="flex items-start gap-2">
                                        <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                               id="cat-{{ $category->id }}"
                                               class="category-check mt-1 h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500"
                                               @checked(in_array($category->id, (array) old('categories', [])))>
                                        <div class="flex-1">
                                            <label for="cat-{{ $category->id }}" class="text-sm font-semibold text-slate-800">
                                                {{ $category->name }}
                                                <span class="ml-1 inline-flex rounded-full bg-brand-50 px-2 py-0.5 text-xs font-medium text-brand-700">
                                                    {{ $category->points_per_kg }} poin/kg
                                                </span>
                                            </label>
                                            <input type="number" step="0.1" min="0.1"
                                                   name="estimated_weight[{{ $category->id }}]"
                                                   placeholder="Perkiraan berat (kg)"
                                                   value="{{ old('estimated_weight.' . $category->id) }}"
                                                   class="weight-input mt-2 form-control @error('estimated_weight.' . $category->id) input-error @enderror">
                                            @error('estimated_weight.' . $category->id)
                                                <div class="form-error">{{ $message }}</div>
                                            @enderror
                                            @if ($category->description)
                                                <p class="form-text">{{ $category->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('categories')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="notes" class="form-label">Catatan (opsional)</label>
                        <textarea id="notes" name="notes" rows="2" maxlength="1000" class="form-control @error('notes') input-error @enderror">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-full">Kirim Pengajuan</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkboxes = document.querySelectorAll('.category-check');

            const syncInput = function (checkbox) {
                const input = document.querySelector('input[name="estimated_weight[' + checkbox.value + ']"]');
                if (!input) return;

                input.disabled = !checkbox.checked;
                input.required = checkbox.checked;
                if (!checkbox.checked) input.value = '';
            };

            checkboxes.forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    syncInput(checkbox);
                });
                syncInput(checkbox);
            });
        });
    </script>
@endpush