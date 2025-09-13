<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDriverRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return $this->user()->can('create');
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'driver_account_name' => ['string', 'required', 'max:255'],
            'account_status' => ['string', 'nullable', 'max:255'],
            'driver_type' => ['required', 'string', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
        ];
    }
}
