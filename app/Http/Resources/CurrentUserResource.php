<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CurrentUserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $role = $this->roles->firstWhere('division_id', $this->division?->id);

        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'office' => $this->office,
            'receiveMail' => $this->receiveMail,
            'roles' => $this->roles->map(fn($role) => [
                'division' => [
                    'id' => $role->pivot->division_id
                ],
                'role' => [
                    'id' => $role->id,
                    'code' =>  $role->code,
                    'name' =>  $role->name,
                ]
            ]),
        ];
    }
}
