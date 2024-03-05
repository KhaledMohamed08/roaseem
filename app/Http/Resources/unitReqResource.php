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
            "unitType"=>$this->unit_types,
            "status"=>isset($this->status) ? $this->status:null,
            "purpose"=>isset($this->purpose) ? $this->purpose:null,
            "maxArea"=>$this->max_area,
            "minArea"=>$this->min_area,
            "maxPrice"=>$this->max_price,
            "minPrice"=>$this->min_price,
            "description"=>$this->description,
            "adPeriod"=>Carbon::parse($this->ad_period)->format('d/m/y'),
            "entityType"=>$this->entity_type,
            "city"=>$this->city->name,
            "created_at"=>Carbon::parse($this->created_at)->format('d/m/Y'),
            // "company"=>UserResource::collection($this->whenLoaded($this->unitReqUser->user)),

            "companies" => UserResource::collection($this->unitReqUser)->map(function ($unitReqUser) {
                return [
                    'id' => $unitReqUser->user->id,
                    'name' => $unitReqUser->user->name,
                ];
            }),
            
            // "companies" => UserResource::collection(
            //     $this->unitReqUser->where('user_id', '!=', $user->id)
            //     )->map(function ($unitReqUser) {
            //     return [
            //         'id' => $unitReqUser->user->id,
            //         'name' => $unitReqUser->user->name,
            //     ];
            // }),
        ];
    }
}
