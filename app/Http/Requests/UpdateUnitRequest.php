<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUnitRequest extends FormRequest
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
            'region_id' => [
                'required',
                'numeric',
            ],
            'address' => [
                'required',
            ],
            'latitude' => [
                'required',
            ],
            'longitude' => [
                'required',
            ],
            'ad_title' => [
                'required',
            ],
            'unit_type_id' => [
                'required',
                'numeric',
            ],
            'unit_status_id' => [
                'required',
                'numeric',
            ],
            'unit_purpose_id' => [
                'required',
                'numeric',
            ],
            'unit_interface_id' => [
                'required',
                'numeric',
            ],
            'floor_number' => [
                'required',
                'numeric',
            ],
            'created_year' => [
                'required',
                'numeric'
            ],
            'area' => [
                'required',
                'numeric',
            ],
            'street_width' => [
                'required',
                'numeric',
            ],
            'unit_payment_id' => [
                'required',
                'numeric',
            ],
            'price' => [
                'required',
                'numeric',
            ],
            'descreption' => [
                'required',
            ],
            'services' => [
                'required',
            ],
            'bedrooms' => [
                'required',
                'numeric',
            ],
            'living_rooms' => [
                'required',
                'numeric'
            ],
            'bathrooms' => [
                'required',
                'numeric',
            ],
            'kitchens' => [
                'required',
                'numeric',
            ],
            'licensor_name' => [
                'required',
            ],
            'advertising_license_number' => [
                'required',
                'numeric'
            ],
            'brokerage_documentation_license_number' => [
                'required',
                'numeric',
            ],
            'rights_and_obligations' => [
                'required'
            ],
            'main_image' => [
                'file',
                'mimetypes:image/jpeg,image/jpg,image/png',
                'max:10240',
            ],
            'images.*' => [
                'file',
                'mimetypes:image/jpeg,image/jpg,image/png',
                'max:10240',
            ]
        ];
    }
}
