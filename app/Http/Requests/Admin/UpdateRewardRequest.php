<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRewardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'category' => ['required', 'in:saldo,barang'],
            'nominal' => ['required_if:category,saldo', 'nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'points_required' => ['required', 'integer', 'min:1'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('category') === 'barang') {
            $this->merge(['nominal' => null]);
        }
    }
}
