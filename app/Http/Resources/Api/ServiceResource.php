<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'name' => $this->name,
            'description' => (string) $this->description,
            'price' => $this->price,  
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'duration' => $this->duration,
            'status' => $this->status,
            'rating' => $this->rating,
            'reviews_count' => $this->reviews_count,

            //relationship
            'category' => new CategoryResource($this->whenLoaded('category')),
            'provider' => new UserResource($this->whenLoaded('provider')),
        ];
    }
}
