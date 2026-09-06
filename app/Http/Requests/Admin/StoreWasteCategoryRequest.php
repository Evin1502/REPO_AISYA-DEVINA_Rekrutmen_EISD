<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreWasteCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', 'unique:waste_categories,name'],
            'description' => ['nullable', 'string', 'max:1000'],
            'points_per_kg' => ['required', 'integer', 'min:1'],
        ];
    }
}