<?php

namespace App\Http\Resources;

use App\Models\UnitService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $originUrls = [];
        foreach ($this->getMedia('property-images') as $media) {
            $image = [
                'id' => $media->id,
                'url' => $media->original_url
            ];
            array_push($originUrls, $image);
        }
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'desc' => $this->desc,
            'region' => [
                'id' => $this->region->id,
                'name' => $this->region->name
            ],
            'city' => [
                'id' => $this->region->city->id,
                'name' => $this->region->city->name,
            ],
            'type' => [
                'id' => $this->type->id,
                'name' => $this->type->name,
            ],
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'license_name' => $this->license_name,
            'license_end_date' => $this->license_end_date,
            'brokerage_contract_number' => $this->brokerage_contract_number,
            'license_number' => $this->license_number,
            'license_creation_date' => $this->license_creation_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'images' => $originUrls,
        ];
    }
}
