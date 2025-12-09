<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
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
            'chat_content' => ['string', 'required'],
            'ride_request_id' => ['numeric', 'required'],
            'driver_id' => ['numeric', 'required'],
            'passenger_id' => ['numeric', 'required'],
            'content_type' => ['string', 'required'],
        ];
    }
}
