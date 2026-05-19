<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscribeTimeLineResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date_start' => $this->start_at->format('H:i'),
            'shift' => round((
                $this->start_at->minute > 30
                    ? $this->start_at->minute - 30
                    : $this->start_at->minute
            ) / 30 * 100),
            'service' => [
                'duration' => $this->service->duration->format('H:i'),
                'name' => $this->service->name,
            ],
        ];
    }
}
