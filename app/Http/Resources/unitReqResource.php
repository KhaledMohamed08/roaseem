<?php

namespace App\Http\Resources;

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
            "name"=>$this->name,
            "phone"=>$this->phone,
            "email"=>$this->email,
            "unitType"=>$this->unit_type,
            "status"=>isset($this->status) ? $this->status:null,
            "purpose"=>isset($this->purpose) ? $this->purpose:null,
            "area"=>$this->area,
            "price"=>$this->price,
            "description"=>$this->description,
            "adPeriod"=>$this->ad_period,
            "entityType"=>$this->entity_type,
            "city_id"=>$this->city_id,
        ];
    }
}
