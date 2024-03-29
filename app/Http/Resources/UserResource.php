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
            'image' => $this->getFirstMediaUrl('logo') ?? 'no image',
        ];

        if ($this->role == 'marketer') {
            $user['permissions'] = $this->getAllPermissions();
        }
        
        return $user;
    }
}
