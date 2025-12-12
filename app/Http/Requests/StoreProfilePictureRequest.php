<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProfilePictureRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return auth()->check();
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'profile_picture' => 'required|array|min:1',
            'profile_picture.*' => 'required|image|mimes:jpeg,jpg,png,gif|max:10240', // Max 10MB
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'profile_picture.required' => 'Please select a profile picture to upload.',
            'profile_picture.*.image' => 'The file must be an image.',
            'profile_picture.*.mimes' => 'The image must be a JPEG, JPG, PNG, or GIF file.',
            'profile_picture.*.max' => 'The image size must not exceed 10MB.',
        ];
    }
}
