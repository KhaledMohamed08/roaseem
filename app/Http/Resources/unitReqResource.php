<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class unitReqResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        
        return[
            "id" => $this->id,
            "name"=>$this->name,
            "phone"=>$this->phone,
            "email"=>$this->email,
            "unitType"=>[
                "id" => $this->unitType->id,
               "name" => $this->unitType->name,
            ],

            "status" => isset($this->unitStatus) ? [
                "id" => $this->unitStatus->id,
                "name" => $this->unitStatus->name,
            ] : null,
            
            "purpose" => isset($this->unitPurpose) ? [
                "id" => $this->unitPurpose->id,
                "name" => $this->unitPurpose->name,
            ] : null,
            "maxArea"=>$this->max_area,
            "minArea"=>$this->min_area,
            "maxPrice"=>$this->max_price,
            "minPrice"=>$this->min_price,
            "description"=>$this->description,
            'bedRooms' => $this->bed_rooms,
            'bathRooms' => $this->bath_rooms,
            "adPeriod"=>Carbon::parse($this->ad_period)->format('d/m/y'),
            "entityType"=>$this->entity_type,
            "city"=>[
                "id"=>$this->city->id,
               "name"=> $this->city->name,
            ],

            "created_at"=>Carbon::parse($this->created_at)->format('d/m/Y'),
            // "company"=>UserResource::collection($this->whenLoaded($this->unitReqUser->user)),

            "companies" => UserResource::collection($this->unitReqUser)->map(function ($unitReqUser) {
                return [
                    'id' => $unitReqUser->user->id,
                    'name' => $unitReqUser->user->name,
                ];
            }),
        ];
    }
}
