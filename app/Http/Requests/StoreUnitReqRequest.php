<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUnitReqRequest extends FormRequest
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
            'name'=>"required|string",
            'phone'=>"required",
            'email'=>"required|email",
            'unitType'=>"required",
            'city_id'=>"required",
            'maxArea'=>"required|numeric",
            'minArea'=>"required|numeric",
            'maxPrice'=>"required|numeric",
            'minPrice'=>"required|numeric",
            'description'=>"required",
            'bedRooms' => "required",
            'bathRooms' => "required",
            'adPeriod'=>"nullable",
            'entity_type'=>"required",
            'companies'=>"nullable",
        ];
    }
}
