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
            'unitType_id'=>"required",
            'city_id'=>"required",
            'maxArea'=>"required|numeric",
            'minArea'=>"required|numeric",
            'maxPrice'=>"required|numeric",
            'minPrice'=>"required|numeric",
            'description'=>"",
            'adPeriod_id'=>"required|integer",
            'entity_type'=>"required",
            'companies'=>"nullable",
        ];
        
    }
}
