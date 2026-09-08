@extends('layouts.app')

@section('title', 'Pengajuan Pengambilan Sampah - TemJi')

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="card">
            <div class="card-header">
                <h1 class="text-lg font-bold text-on-surface">Pengajuan Pengambilan Sampah</h1>
                <p class="text-sm text-on-surface-variant">Pilih tanggal & slot waktu, isi kategori sampah, dan tentukan alamat penjemputan.</p>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <x-alert type="error">
                        <strong>Data tidak valid.</strong>
                        Silakan perbaiki isian di bawah lalu kirim kembali pengajuan Anda.
                    </x-alert>
                @endif

                <form method="POST" action="{{ route('resident.pickup-requests.store') }}" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label for="pickup_date" class="form-label">Tanggal Penjemputan</label>
                            <input type="date" id="pickup_date" name="pickup_date"
                                   min="{{ $minDate }}" max="{{ $maxDate }}"
                                   value="{{ old('pickup_date') }}"
                                   class="form-control @error('pickup_date') input-error @enderror">
                            @error('pickup_date')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label">Waktu Penjemputan (Slot)</label>
                            <div class="grid grid-cols-1 gap-2">
                                @foreach ($timeSlots as $slot)
                                    <label for="slot-{{ $loop->index }}"
                                           class="slot-card flex cursor-pointer items-center gap-2 rounded-lg border border-outline-variant p-3 transition-colors has-checked:border-brand-500 has-checked:bg-brand-50">
                                        <input type="radio" name="time_slot" value="{{ $slot['key'] }}"
                                               id="slot-{{ $loop->index }}"
                                               class="slot-radio h-5 w-5 rounded border-outline-variant text-brand-600 focus:ring-2 focus:ring-brand-500"
                                               @checked(old('time_slot') === $slot['key'])
                                               disabled>
                                        <span class="text-sm font-semibold text-on-surface">{{ $slot['label'] }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <p class="form-text mt-2">Slot yang penuh atau sudah lewat tidak dapat dipilih. Maksimal {{ $capacity }} pengajuan per slot.</p>
                            @error('time_slot')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="address" class="form-label">Alamat Penjemputan</label>
                        <input type="text" id="address" name="address" value="{{ old('address', auth()->user()->address) }}" required
                               class="form-control @error('address') input-error @enderror">
                        @error('address')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="area" class="form-label">Wilayah</label>
                        <select id="area" name="area" required class="form-control @error('area') input-error @enderror">
                            <option value="">Pilih wilayah...</option>
                            @foreach (config('temji.service_areas') as $area)
                                <option value="{{ $area }}" @selected(old('area') === $area)>{{ $area }}</option>
                            @endforeach
                        </select>
                        <p class="form-text">Dipakai admin untuk memantau cakupan & volume sampah per wilayah kota.</p>
                        @error('area')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label">Kategori Sampah & Perkiraan Berat (kg)</label>
                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                            @foreach ($wasteCategories as $category)
                                {{--
                                    Guideline "Touch & Interaction" (ui-ux-pro-max): target sentuh
                                    minimal 44x44px. Seluruh kartu dibungkus <label> supaya area
                                    klik jauh lebih besar dari checkbox 16x16px doang, dan state
                                    "dipilih" ditandai visual (border + background), bukan cuma
                                    checkbox-nya yang kecentang.
                                --}}
                                <label for="cat-{{ $category->id }}"
                                       class="flex cursor-pointer items-start gap-2 rounded-lg border border-outline-variant p-3 transition-colors has-checked:border-brand-500 has-checked:bg-brand-50">
                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                           id="cat-{{ $category->id }}"
                                           class="category-check mt-1 h-5 w-5 rounded border-outline-variant text-brand-600 focus:ring-2 focus:ring-brand-500"
                                           @checked(in_array($category->id, (array) old('categories', [])))>
                                    <div class="flex-1">
                                        <span class="text-sm font-semibold text-on-surface">
                                            {{ $category->name }}
                                            <span class="ml-1 inline-flex rounded-full bg-brand-50 px-2 py-0.5 text-xs font-medium text-brand-700">
                                                {{ $category->points_per_kg }} poin/kg
                                            </span>
                                        </span>
                                        <input type="number" step="0.1" min="0.1"
                                               name="estimated_weight[{{ $category->id }}]"
                                               placeholder="Perkiraan berat (kg)"
                                               value="{{ old('estimated_weight.' . $category->id) }}"
                                               onclick="event.stopPropagation()"
                                               class="weight-input mt-2 form-control @error('estimated_weight.' . $category->id) input-error @enderror">
                                        @error('estimated_weight.' . $category->id)
                                            <div class="form-error">{{ $message }}</div>
                                        @enderror
                                        @if ($category->description)
                                            <p class="form-text">{{ $category->description }}</p>
                                        @endif
                                    </div>
                                </label>
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

            // Slot waktu: hanya slot yang tersedia pada tanggal terpilih yang bisa dipilih.
            const dateInput = document.getElementById('pickup_date');
            const slotInputs = Array.from(document.querySelectorAll('.slot-radio'));
            const availability = @json($availability);

            const refreshSlots = function () {
                const date = dateInput ? dateInput.value : '';

                slotInputs.forEach(function (input) {
                    const available = Boolean(date && availability[date] && availability[date][input.value]);
                    const card = input.closest('.slot-card');

                    input.disabled = !available;
                    if (input.checked && !available) {
                        input.checked = false;
                    }
                    if (card) {
                        card.classList.toggle('opacity-50', !available);
                    }
                    card?.setAttribute('aria-disabled', available ? 'false' : 'true');
                });
            };

            if (dateInput) {
                dateInput.addEventListener('change', refreshSlots);
            }
            refreshSlots();

            // Guideline "Submit Feedback" (ui-ux-pro-max, domain: ux):
            // beri feedback loading & cegah submit ganda saat tombol diklik.
            const form = document.querySelector('form[action="{{ route('resident.pickup-requests.store') }}"]');
            const submitBtn = form?.querySelector('button[type="submit"]');

            form?.addEventListener('submit', function () {
                if (!submitBtn) return;
                submitBtn.disabled = true;
                submitBtn.textContent = 'Mengirim...';
            });
        });
    </script>
@endpush