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
        return [
            "id" => $this->id,
            "first_name" => $this->first_name,
            "middle_name" => $this->middle_name,
            "last_name" => $this->last_name,
            "email" => $this->email,
            "phone" => $this->phone,
            "office" => $this->office,
            "receiveMail" => $this->receiveMail,
            'divisions' => $this->divisions->map(fn($division) => [
                'id' => $division->id,
                'name' => $division->name,
            ]),
            'role' => [
                'id' => $this->roles->first()?->id,
                'code' => $this->roles->first()?->code,
                'name' => $this->roles->first()?->name,
            ],
        ];
    }
}
