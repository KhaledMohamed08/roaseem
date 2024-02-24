<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUnitReqRequest extends FormRequest
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
            'id'=>"required",
            'name'=>"required|string",
            'phone'=>"required",
            'email'=>"required|email",
            'unitType'=>"required",
            'city_id'=>"required",
            'area'=>"required",
            'price'=>"required|numeric",
            'description'=>"required",
            'adPeriod'=>"required",
            'entity_type'=>"required",
            'companies'=>"nullable",
        ];
        
    }
}
