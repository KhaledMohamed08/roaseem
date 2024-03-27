<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAuctionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
            ],
            'desc' => [
                'required',
            ],
            'is_offline' => [
                'required',
            ],
            'link' => [
                'required',
            ],
            'start_date' => [
                'required',
            ],
            'end_date' => [
                'required',
            ],
            'start_time' => [
                'required',
            ],
            'opening_price' => [
                'required',
            ],
            'subscription_fee' => [
                'required',
            ],
            'minimum_bid' => [
                'required',
            ],
            'admin_status' => [
                'required',
            ],
            'status' => [
                'required',
            ],
            'auctioneer_name' => [
                'required',
            ],
            'id_number' => [
                'required',
            ],
            'auction_license_number' => [
                'required',
            ],
        ];
    }
}
