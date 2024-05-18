<?php

namespace App\Http\Resources;

use App\Models\User;
use GuzzleHttp\Client;
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
        $role = $this->role;
        $taxNumber = $this->role === 'company' ? $this->tax_number : null;
        $practicing_number = $this->role === 'practiciner' ? $this->practicing_number : null;

        $user = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'whatsapp' => $this->whatsapp,
            'land_line' => $this->land_line,
            // 'role' => $this->role,
            'role' => $role,
            'is_active' => $this->is_active,
            'tax_number' => $taxNumber,
            'practicing_number' => $practicing_number,
            'about' => $this->about,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // 'image' => $this->getFirstMediaUrl('logo'),
            'longitude' => $this->longitude ?? 'no longitude',
            'latitude' => $this->latitude ?? 'no latitude',
            'address' => $this->address ?? 'no address',
        ];

        $image = $this->getFirstMediaUrl('logo');
        if ($image == "") {
            $user['image'] = 'https://cdn.pixabay.com/photo/2016/08/08/09/17/avatar-1577909_1280.png';
        } else {
            $user['image'] = $image;
        }

        if ($this->role == 'company') {
            $user['unites'] = UserResource::collection($this->unites);
            $user['favorites'] = $this->favorites;
            $user['marketers'] = User::where('role', 'marketer')->where('company_id', $this->id)->get()->each(function ($marketer) {
                $marketer->image = $marketer->getImageUrl();
            });
        }

        if ($this->role == 'marketer') {
            $user['permissions'] = $this->getAllPermissions();
        }

        if ($this->role === 'marketer') {
            $user['company'] = User::where('id', $this->company_id)->first();
        }
        
        return $user;
    }
}
