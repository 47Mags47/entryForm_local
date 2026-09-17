<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserWeekendResource extends JsonResource
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
            'replacement' => [
                'id' => $this->replacement->id,
                'first_name' => $this->replacement->first_name,
                'middle_name' => $this->replacement->middle_name,
                'last_name' => $this->replacement->last_name,
                'full_name' => $this->replacement
                    ? $this->replacement->last_name . ' ' .
                    mb_substr($this->replacement->first_name, 0, 1) . '.' .
                    mb_substr($this->replacement->middle_name, 0, 1) . '.'
                    : null,
            ],
            'date_start' => $this->date_start->format('Y-m-d'),
            'date_end' => $this->date_end->format('Y-m-d'),
        ];
    }
}
