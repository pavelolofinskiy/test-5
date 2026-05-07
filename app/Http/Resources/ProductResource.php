<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Product */
class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => [
                'cents' => $this->price_cents,
                'currency' => $this->currency,
                'formatted' => $this->price()->format(),
            ],
            'stock' => $this->stock,
            'category' => CategoryResource::make($this->whenLoaded('category')),
        ];
    }
}
