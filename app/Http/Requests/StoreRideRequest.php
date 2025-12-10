<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

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
        Log::debug('Processing request...');
        Log::debug($this->all());

        return [
            'ride_name' => ['required', 'string', 'max:255'],
            'status' => ['nullable'],
            'minimum_fare' => ['required', 'decimal:0,4'],
            'fare_rate' => ['required', 'decimal:0,4'],
            'vehicle_id' => ['required', 'numeric'],
            'rating' => ['nullable'],
            'longitude' => ['required', 'array', 'min:1'],
            'latitude' => ['required', 'array', 'min:1'],
            'order' => ['required', 'array', 'min:1'],
            'longitude.*' => ['required', 'numeric'],
            'latitude.*' => ['required', 'numeric'],
            'order.*' => ['required', 'numeric'],
        ];
    }
}
