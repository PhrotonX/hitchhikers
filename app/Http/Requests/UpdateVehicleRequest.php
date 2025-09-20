<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehicleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('vehicle'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'plate_number' => ['required', 'string', 'max:255', Rule::unique(User::class)->ignore($this->route('vehicle')->id)],
            'vehicle_name' => ['required', 'string', 'mmax:255'],
            'vehicle_model' => ['required', 'string', 'max:255'],
            'vehicle_brand' => ['required', 'string', 'max:255'],
            'capacity' => ['numeric'],
            'coordinates' => ['nullable'],
            'color' => ['required', 'string'],
            'type' => ['required', 'string', 'max:255'],

        ];
    }
}
