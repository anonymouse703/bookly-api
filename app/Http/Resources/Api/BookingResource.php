<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $timezone = getModelTimezone($request->user());

        return [
            'id' => $this->id,
            'user_id' => (string) $this->user_id,
            'provider_id' => (string) $this->provider_id,
            'service_id' => (string) $this->service_id,
            'reference_number' => $this->reference_number,
            
            'booking_date' => $this->booking_date   ? $this->booking_date->format('Y-m-d') : null,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'status' => $this->status,
            'notes' => $this->notes,
            'created_at' => $this->created_at?->timezone($timezone)?->format('Y-m-d H:i:s') ?? null,
            'rescheduled_at' => $this->rescheduled_at?->timezone($timezone)?->format('Y-m-d H:i:s') ?? null,

            //relationship
            'service' => new ServiceResource($this->whenLoaded('service')),
            'provider' => new UserResource($this->whenLoaded('provider')),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
