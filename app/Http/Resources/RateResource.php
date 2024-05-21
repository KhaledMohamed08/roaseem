<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RateResource extends JsonResource
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
            'rater' =>[
                'id' => $this->rater->id,
                'name' => $this->rater->name,
            ],
            'image' => $this->rater->getFirstMediaUrl('logo') ?? null,
            'rate' => $this->rate,
            'comment' => $this->comment,
            'created_at' => Carbon::parse($this->created_at)->format('Y M d'),
        ];
    }
}
