<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public static $wrap = false;
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
            'description' => $this->description,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'image' => $this->getFirstMediaUrl('images') ?: null,
            'images' => $this->getMedia('images')->map(function ($image) {
                return [
                    'id' => $image->id,
                    'thumb' => $image->hasGeneratedConversion('thumb') ? $image->getUrl('thumb') : $image->getUrl(),
                    'small' => $image->hasGeneratedConversion('small') ? $image->getUrl('small') : $image->getUrl(),
                    'large' => $image->hasGeneratedConversion('large') ? $image->getUrl('large') : $image->getUrl(),
                ];
            }),
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'departement' =>
            [
                'id' => $this->departement->id,
                'name' => $this->departement->name,
            ],
            'variationTypes' => $this->variationTypes->map(function ($variationType) {
                return [
                    'id' => $variationType->id,
                    'name' => $variationType->name,
                    'type' => $variationType->type,
                    'options' => $variationType->options->map(function ($option) {
                        return [
                            'id' => $option->id,
                            'name' => $option->name,
                            'images' => $option->getMedia('images')->map(function ($image) {
                                return [
                                    'id' => $image->id,
                                    'thumb' => $image->hasGeneratedConversion('thumb') ? $image->getUrl('thumb') : $image->getUrl(),
                                    'small' => $image->hasGeneratedConversion('small') ? $image->getUrl('small') : $image->getUrl(),
                                    'large' => $image->hasGeneratedConversion('large') ? $image->getUrl('large') : $image->getUrl(),
                                ];
                            }),
                        ];
                    }),
                ];
            }),
            'variations' => $this->variations->map(function ($variation) {
                return [
                    'id' => $variation->id,
                    'variation_type_option_ids' => $variation->variation_type_option_ids,
                    'quantity' => $variation->quantity,
                    'price' => $variation->price,
                ];
            }),
        ];
    }
}
