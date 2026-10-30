<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DivisionUnavailablePeriodResource extends JsonResource
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
            'division' => $this->division,
            'date_start' => $this->date_start->format('Y-m-d'),
            'date_end' => $this->date_end->format('Y-m-d'),
        ];

    }
}
