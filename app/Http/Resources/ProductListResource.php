<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductListResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'image' => $this->hasMedia('images') && $this->getFirstMedia('images')->hasGeneratedConversion('thumb')
                ? $this->getFirstMediaUrl('images', 'thumb')
                : $this->getFirstMediaUrl('images'),
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'departement' =>
            [
                'id' => $this->departement->id,
                'name' => $this->departement->name,
            ]
        ];
    }
}
