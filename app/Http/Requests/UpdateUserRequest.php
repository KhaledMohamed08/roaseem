<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $userId = auth()->user()->id;
        $user = User::find($userId);
        return [
            'name' => 'required',
            // 'phone' => [
            //     'required',
            //     Rule::unique('users')->ignore($userId, 'id'),
            // ],
            'email' => [
                // 'required',
                // Rule::unique('users')->where(function ($query) use ($userId) {
                //     return $query->where('id', '!=', $userId);
                // }),
                Rule::unique('users')->ignore($user),
            ],
            'about' => '',
            'whatsapp' => '',
            'land_line' => '',
            'longitude' => '',
            'latitude' => '',
            'address' => '',
        ];
    }
}
