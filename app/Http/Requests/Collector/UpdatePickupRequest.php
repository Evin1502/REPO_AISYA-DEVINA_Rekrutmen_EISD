<?php

namespace App\Http\Requests\Collector;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePickupRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $pickupRequest = $this->route('pickupRequest');

        if (! $user || ! $user->isCollector()) {
            return false;
        }

        return $pickupRequest && $pickupRequest->collector_id === $user->id;
    }

    /**
     * Rules are intentionally loose — status transitions (approved→scheduled→collected)
     * are validated in the controller within a DB transaction.
     */
    public function rules(): array
    {
        return [
            'scheduled_at' => ['nullable', 'date'],
            'actual_weight' => ['nullable', 'array'],
            'actual_weight.*' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'actual_weight.*.min' => 'Berat riil tidak boleh negatif.',
        ];
    }
}