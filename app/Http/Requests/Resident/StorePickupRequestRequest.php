<?php

namespace App\Http\Requests\Resident;

use App\Models\PickupRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class StorePickupRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isResident() ?? false;
    }

    public function rules(): array
    {
        $maxDate = Carbon::today()->addDays(PickupRequest::SLOT_WINDOW_DAYS - 1)->toDateString();
        $slotKeys = implode(',', array_column(PickupRequest::TIME_SLOTS, 'key'));

        return [
            'pickup_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today', 'before_or_equal:'.$maxDate],
            'time_slot' => ['required', 'in:'.$slotKeys],
            'address' => ['required', 'string', 'max:255'],
            'area' => ['nullable', 'string'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:waste_categories,id'],
            'estimated_weight' => ['nullable', 'array'],
            'estimated_weight.*' => ['nullable', 'numeric', 'min:0.1'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $categories = (array) $this->input('categories', []);
            $weights = (array) $this->input('estimated_weight', []);

            foreach ($categories as $categoryId) {
                $weight = $weights[$categoryId] ?? null;

                if ($weight === null || $weight === '' || (float) $weight < 0.1) {
                    $validator->errors()->add(
                        'estimated_weight.'.$categoryId,
                        'Isi perkiraan berat untuk setiap kategori yang dipilih.'
                    );
                }
            }

            $date = (string) $this->input('pickup_date', '');
            $slotKey = (string) $this->input('time_slot', '');

            if ($date !== '' && $slotKey !== '' && ! PickupRequest::isSlotAvailable($date, $slotKey)) {
                $slot = PickupRequest::slotByKey($slotKey);

                $validator->errors()->add(
                    'time_slot',
                    'Slot waktu '.($slot['label'] ?? $slotKey).' pada tanggal tersebut sudah penuh atau tidak tersedia. Silakan pilih slot lain.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'pickup_date.required' => 'Tanggal penjemputan wajib diisi.',
            'pickup_date.date_format' => 'Format tanggal penjemputan tidak valid.',
            'pickup_date.after_or_equal' => 'Tanggal penjemputan tidak boleh sebelum hari ini.',
            'pickup_date.before_or_equal' => 'Tanggal penjemputan melebihi batas jadwal yang tersedia.',
            'time_slot.required' => 'Pilih slot waktu penjemputan.',
            'time_slot.in' => 'Slot waktu yang dipilih tidak valid.',
            'address.required' => 'Alamat penjemputan wajib diisi.',
            'area.required' => 'Pilih wilayah penjemputan.',
            'area.in' => 'Wilayah yang dipilih tidak tersedia.',
            'categories.required' => 'Pilih minimal satu kategori sampah.',
            'categories.min' => 'Pilih minimal satu kategori sampah.',
            'estimated_weight.*.numeric' => 'Perkiraan berat harus berupa angka.',
            'estimated_weight.*.min' => 'Perkiraan berat minimal 0.1 kg.',
        ];
    }
}
