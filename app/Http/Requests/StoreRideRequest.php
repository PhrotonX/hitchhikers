<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRideRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return this
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ride_name' => ['required', 'string', 'max:255'],
            'status' => ['nullable'],
            'fare_rate' => ['required', 'decimal:0,4'],
            'vehicle_id' => ['required', 'numeric'],
            'rating' => ['nullable'],
            'longitude[]' => ['required', 'numeric'],
            'latitude[]' => ['required', 'numeric'],
            'order[]' => ['required', 'numeric'],
        ];
    }
}
