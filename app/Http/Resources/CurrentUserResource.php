<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CurrentUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'email'=> $this->email,
            'phone' => $this->phone,
            'office' => $this->office,
            'division' => $this->division !== null
                ? [
                    "id" => $this->division->id,
                    "name" => $this->division->name,
                ]
                : null,
            'role'=> [
                'id'=> $this->role->id,
                'code'=> $this->role->code,
                'name' => $this->role->name,
            ],
        ];
    }
}
