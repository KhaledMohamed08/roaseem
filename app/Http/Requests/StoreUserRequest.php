<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'name' => 'required',
            'email' => [
                'required',
                'unique:users',
                'email',
            ],
            'phone' => [
                'required',
                'unique:users',
                // 'regex:/^[0-9]+$/',
                'numeric',
                'min:8',
            ],
            'password' => [
                'required',
                'min:6',
                'confirmed',
            ],
            'role' => [
                'required_if:role,company',
                'string',
            ],
            'tax_number' => [
                'required_if:role,company',
                'numeric',
            ]
        ];
    }

    public function messages(): array
    {
        return [
            // 'phone.regex' => 'phone number must me only numbers',
        ];
    }
}
