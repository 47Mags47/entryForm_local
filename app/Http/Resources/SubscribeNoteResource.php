<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscribeNoteResource extends JsonResource
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
            "note" => $this->note,
            "worker" => [
                'id' => $this->worker?->id,
                "name" => implode(' ', array_filter([
                    $this->worker?->last_name,
                    $this->worker?->first_name ? mb_substr($this->worker?->first_name, 0, 1) . '.' : null,
                    $this->worker?->middle_name ? mb_substr($this->worker?->middle_name, 0, 1) . '.' : null,
                ])),
            ],
        ];
    }
}
