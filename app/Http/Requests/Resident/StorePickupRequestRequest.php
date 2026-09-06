<?php

namespace App\Http\Requests\Resident;

use Illuminate\Foundation\Http\FormRequest;

class StorePickupRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isResident() ?? false;
    }

    public function rules(): array
    {
        return [
            'address' => ['required', 'string', 'max:255'],
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
                        'estimated_weight.' . $categoryId,
                        'Isi perkiraan berat untuk setiap kategori yang dipilih.'
                    );
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'categories.required' => 'Pilih minimal satu kategori sampah.',
            'categories.min' => 'Pilih minimal satu kategori sampah.',
            'estimated_weight.*.numeric' => 'Perkiraan berat harus berupa angka.',
            'estimated_weight.*.min' => 'Perkiraan berat minimal 0.1 kg.',
        ];
    }
}