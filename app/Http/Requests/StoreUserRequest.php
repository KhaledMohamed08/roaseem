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
            'whatsapp' => [
                'numeric'
            ],
            'land_line' => [
                'numeric'
            ],
            'about' => [
                
            ],
            'role' => [
                // 'required_if:role,company',
                'required',
                'string',
                'in:user,company,marketer,practiciner'
            ],
            'tax_number' => [
                'required_if:role,company',
                'numeric',
            ],
            'practicing_number' => [
                'required_if:role,practiciner',
                'numeric',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            // 'phone.regex' => 'phone number must me only numbers',
            'role.in' => 'The selected role is invalid, select from (user, company, marketer or practiciner)'
        ];
    }
}
