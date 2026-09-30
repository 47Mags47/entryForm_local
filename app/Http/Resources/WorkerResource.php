<?php

namespace App\Http\Resources;

use App\Models\Service;
use App\Models\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $role = $this->roles->first(function ($role) use ($request) {
            return $role->pivot->division_id === $request->division?->id;
        });

        $serviceIds = UserService::query()
            ->where('user_id', $this->id)
            ->pluck('service_id');

        return [
            "id" => $this->id,
            "first_name" => $this->first_name,
            "middle_name" => $this->middle_name,
            "last_name" => $this->last_name,
            'full_name' => $this->last_name . ' ' .
                mb_substr($this->first_name, 0, 1) . '.' .
                mb_substr($this->middle_name, 0, 1) . '.',
            "email" => $this->email,
            "phone" => $this->phone,
            "office" => $this->office,
            'deleted_at' => $this->divisions()
                ->wherePivot('division_id', $request->division?->id)
                ->first()?->pivot?->deleted_at,
            'is_subscribe_available' => (bool) $this->divisions()
                ->wherePivot('division_id', $request->division?->id)
                ->first()?->pivot?->is_subscribe_available,
            'role' => $role !== null
                ? [
                    'id' => $role->id,
                    'code' => $role->code,
                    'name' => $role->name,
                ]
                : null,
            'services' => $this->services
                ->whereIn('id', $serviceIds)
                ->map(function (Service $service) {
                    return [
                        'id' => $service->id,
                        'name' => $service->name,
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
            "weekends" => $this->weekends->toResourceCollection()
        ];
    }
}
