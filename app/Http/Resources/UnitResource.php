<?php

namespace App\Http\Resources;

use App\Models\UnitService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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
            // array_push($originUrls, $media->original_url);
            // $originUrls[$media->original_url] = $media->id;
            $image = [
                'id' => $media->id,
                'url' => $media->original_url
            ];
            array_push($originUrls, $image);
        }

        $services = [];
        foreach ($this->services as $service) {
            $service = UnitService::find($service->service_id);
            array_push($services, [
                'id' => $service->id,
                'name' => $service->name
            ]);
        }
        $unit = [
            'id' => $this->id,
            'user_name' => $this->user->name,
            'country' => $this->region->city->country->name,
            'city' => $this->region->city->name,
            'region' => $this->region->name,
            'region_id' => $this->region_id,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'ad_title' => $this->ad_title,
            'type' => $this->type->name,
            'type_id' => $this->unit_type_id ,
            'status' => $this->status->name,
            'status_id' => $this->unit_status_id,
            'purpose' => $this->purpose->name,
            'purpose_id' => $this->unit_purpose_id,
            'interface' => $this->interface->name,
            'interface_id' => $this->unit_interface_id,
            'floor_number' => $this->floor_number,
            'created_year' => $this->created_year,
            'license_start' => $this->license_start,
            'license_end' => $this->license_end,
            'area' => $this->area,
            'street_width' => $this->street_width,
            'payment_method' => $this->payment_method,
            'payment_id' => $this->unit_payment_id,
            'price' => $this->price,
            'descreption' => $this->descreption,
            // 'services' => $this->services,
            'services' => $services,
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
            'main_image' => [
                'id' => $this->getFirstMedia('unit-Main-image')->id ?? null,
                'url' => !empty($this->getFirstMediaUrl('unit-Main-image'))?$this->getFirstMediaUrl('unit-Main-image'):null,
            ],
            'is_favorite' => false,
            // 'images' => $this->getMedia('images')->getUrl(),
            'images' => $originUrls,
            //'user' => $this->user,
            'unit_description' => [
                'price' => $this->price,
                'bedrooms' => $this->bedrooms,
                'living_rooms' => $this->living_rooms,
                'bathrooms' => $this->bathrooms,
                'kitchens' => $this->kitchens,
                'created_year' => $this->created_year,
                'area' => $this->area,
                'street_width' => $this->street_width,
                'interface' => $this->interface->name,
                'floor_number' => $this->floor_number,
            ]
        ];

        if (auth()->guard('sanctum')->check()) {
            $user = auth()->guard('sanctum')->user();
            $favorites = $user->favorites;

            if ($favorites->contains($this->id)) {
                $unit['is_favorite'] = true;
            }
        }
        
        

        return $unit;
    }
}
