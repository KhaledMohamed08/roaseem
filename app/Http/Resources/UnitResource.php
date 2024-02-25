<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $originUrls = [];
        foreach ($this->getMedia('images') as $media) {
            array_push($originUrls, $media->original_url);
        }

        return [
            'id' => $this->id,
            'user_name' => $this->user->name,
            'country' => $this->region->city->country->name,
            'city' => $this->region->city->name,
            'region' => $this->region->name,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'ad_title' => $this->ad_title,
            'unit_type' => $this->unit_type,
            'contract_type' => $this->contract_type,
            'purpos' => $this->purpos,
            'interface' => $this->interface,
            'floor_number' => $this->floor_number,
            'area' => $this->area,
            'street_width' => $this->street_width,
            'payment_method' => $this->payment_method,
            'price' => $this->price,
            'descreption' => $this->descreption,
            'services' => $this->services,
            'bedrooms' => $this->bedrooms,
            'living_rooms' => $this->living_rooms,
            'bathrooms' => $this->bathrooms,
            'kitchens' => $this->kitchens,
            'licensor_name' => $this->licensor_name,
            'advertising_license_number' => $this->advertising_license_number,
            'brokerage_documentation_license_number' => $this->brokerage_documentation_license_number,
            'rights_and_obligations' => $this->rights_and_obligations,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'main_image' => $this->getFirstMediaUrl('unit-Main-image'),
            // 'images' => $this->getMedia('images')->getUrl(),
            'images' => $originUrls,
        ];
    }
}
