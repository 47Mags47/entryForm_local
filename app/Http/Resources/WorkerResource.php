<?php

namespace App\Http\Resources;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class WorkerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $role = $this->roles->first(function($role) use ($request){
            return $role->pivot->division_id === $request->division->id;
        });

        return [
            "id" => $this->id,
            "first_name" => $this->first_name,
            "middle_name" => $this->middle_name,
            "last_name" => $this->last_name,
            "email" => $this->email,
            'deleted_at' => $this->deleted_at,
            'role' => [
                'id' => $role->id,
                'code' => $role->code,
                'name' => $role->name,
            ],
            'services' => $this->services->map(function (Service $service) {
                return [
                    'id' => $service->id,
                    'name' => $service->name
                ];
            }),
            'shedules' => $this->shedules->map(function ($shedule) {
                return [
                    $shedule->dayOfTheWeek->code => [
                        'date_start' => $shedule->date_start->format('H:i'),
                        'date_end' => $shedule->date_end->format('H:i'),
                        'lunch_start' => $shedule->lunch_start?->format('H:i'),
                        'lunch_end' => $shedule->lunch_end?->format('H:i'),
                    ],
                ];
            })->collapse(),
        ];
    }
}
