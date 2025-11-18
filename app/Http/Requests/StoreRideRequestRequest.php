<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRideRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ride_id' => ['required', 'numeric'],
            // 'sender_user_id' => ['required', 'numeric'],
            'destination_id' => ['nullable', 'numeric'],
            'from_latitude' => ['nullable', 'decimal:10,7'],
            'from_longitude' => ['nullable', 'decimal:10,7'],
            'to_latitude' => ['nullable', 'decimal:10,7'],
            'to_longitude' => ['nullable', 'decimal:10,7'],
            'pickup_at' => ['required', 'string', 'max:1000'],
            'time' => ['required'],
            'message' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
