<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProfitLogsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 'driver_id' => ['required', 'numeric', 'exists:drivers,id'],
            'ride_id' => ['required', 'numeric', 'exists:rides,id'],
            'ride_request_id' => ['required', 'numeric', 'exists:ride_requests,id'],
            'from_latitude' => ['nullable', 'decimal:10,7'],
            'from_longitude' => ['nullable', 'decimal:10,7'],
            'from_address' => ['nullable', 'decimal:10,7'],
            'to_latitude' => ['nullable', 'decimal:10,7'],
            'to_longitude' => ['nullable', 'decimal:10,7'],
            'to_address' => ['nullable', 'decimal:10,7'],
            'profit' => ['required', 'decimal:10,2'],
        ];
    }
}
