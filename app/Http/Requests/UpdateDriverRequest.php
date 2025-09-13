<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDriverRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('driver'));
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
            'company' => ['max:255', Rule::requiredIf($this->input('driver_type') != 'ordinary_driver')],
        ];
    }
}
