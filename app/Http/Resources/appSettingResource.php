<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class appSettingResource extends JsonResource
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
            'content' => $this->content ?? null,
            'email' => $this->email ?? null,
            'phone' => $this->phone ?? null,
            'facebook' => $this->facebook ?? null,
            'instagram' => $this->instgram ?? null,
        ];
    }
}
