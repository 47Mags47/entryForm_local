<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FrameResource extends JsonResource
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
            'frame' => '<iframe src="' . config('app.frame_server.url') . '/frames/' . $this->token . '/subscribes/create' .'"/>',
            'status' => [
                'id' => $this->status?->id,
                'code' => $this->status?->code,
                'name' => $this->status?->name,
            ],
            'division' => [
                'id' => $this->division?->id,
                'name' => $this->division?->name,
            ],
        ];
    }
}
