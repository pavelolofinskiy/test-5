<?php

declare(strict_types=1);

namespace App\Models;

use App\ValueObjects\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price_cents',
        'currency',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price_cents' => 'integer',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function unitPrice(): Money
    {
        return new Money($this->unit_price_cents, $this->currency);
    }

    public function lineTotal(): Money
    {
        return new Money($this->unit_price_cents * $this->quantity, $this->currency);
    }
}
