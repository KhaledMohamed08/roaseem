<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'whatsapp' => $this->whatsapp,
            'land_line' => $this->land_line,
            'role' => $this->role,
            'about' => $this->about,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'image' => $this->getFirstMediaUrl('logo') ?? 'no image',
        ];
    }
}
