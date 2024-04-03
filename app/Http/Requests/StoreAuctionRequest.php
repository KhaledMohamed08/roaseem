<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAuctionRequest extends FormRequest
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

            ],
            'link' => [
                'required',
            ],
            'start_date' => [
                'required',
                'date',
            ],
            'end_date' => [
                'required',
                'date',
            ],
            'start_time' => [
                'required',
                // 'timezone'
            ],
            'opening_price' => [
                'required',
                'numeric'
            ],
            'subscription_fee' => [
                'required',
                'numeric'
            ],
            'minimum_bid' => [
                'required',
                'numeric',
            ],
            'admin_status' => [

            ],
            'status' => [
                
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
            'auction_pdf_file' => [
                'required',
                'file',
            ],
            'main_auction_image' => [
                'required',
                'file'
            ],
            'region_id' => [

            ],
        ];
    }
}
