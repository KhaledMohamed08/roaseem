<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuctionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'desc' => $this->desc,
            'is_offline' => $this->is_offline,
            'link' => $this->link,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'start_time' => $this->start_time,
            'opening_price' => $this->opening_price,
            'subscription_fee' => $this->subscription_fee,
            'minimum_bid' => $this->minimum_bid,
            'admin_status' => $this->admin_status,
            'status' => $this->status,
            'auctioneer_name' => $this->auctioneer_name,
            'id_number' => $this->id_number,
            'auction_license_number' => $this->auction_license_number,
            'pdf_file' => [
                'id' => $this->getFirstMedia('auction_pdf_file')->id ?? '',
                'url' => $this->getFirstMediaUrl('auction_pdf_file'),
            ],
            'main_image' => [
                'id' => $this->getFirstMedia('main_auction_image')->id ?? '',
                'url' => $this->getFirstMediaUrl('main_auction_image'),
            ],
            // 'properties' => $this->properties,
            'properties' => PropertyResource::collection($this->properties),
        ];
    }
}
