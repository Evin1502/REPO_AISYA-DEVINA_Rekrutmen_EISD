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
     * scheduled_at TIDAK divalidasi di sini karena Collector tidak lagi
     * menginput jadwal -- itu sudah ditentukan Resident sejak pengajuan
     * dibuat. Satu-satunya aksi Collector adalah menyelesaikan penjemputan
     * dengan berat riil.
     */
    public function rules(): array
    {
        return [
            'actual_weight' => ['required', 'array', 'min:1'],
            'actual_weight.*' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'actual_weight.*.min' => 'Berat riil tidak boleh negatif.',
        ];
    }
}