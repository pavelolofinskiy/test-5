<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\OrderItem */
class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'unit_price' => [
                'cents' => $this->unit_price_cents,
                'currency' => $this->currency,
                'formatted' => $this->unitPrice()->format(),
            ],
            'line_total' => [
                'cents' => $this->unit_price_cents * $this->quantity,
                'currency' => $this->currency,
                'formatted' => $this->lineTotal()->format(),
            ],
        ];
    }
}
