<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscribeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"            => $this->id,
            "first_name"    => $this->first_name,
            "middle_name"   => $this->middle_name,
            "last_name"     => $this->last_name,
            "email"         => $this->email,
            "phone"         => $this->phone,
            'deleted_at'    => $this->deleted_at,
            "start_at"      => $this->start_at->format('d.m.Y H:i'),
            "service"       => [
                'name'   => $this->service?->name,
            ],
            "division"      => [
                'name'   => $this->division?->name,
            ],
            "worker"        => [
                'id'     => $this->worker->id,
                "name"   => implode(' ', array_filter([
                    $this->worker->last_name,
                    $this->worker->first_name ? mb_substr($this->worker->first_name, 0, 1) . '.' : null,
                    $this->worker->middle_name ? mb_substr($this->worker->middle_name, 0, 1) . '.' : null,
                ])),
                'office' => $this->worker->office
            ],
            "comment"       => $this->comment,
        ];
    }
}
