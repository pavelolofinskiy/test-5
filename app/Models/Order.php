<?php

declare(strict_types=1);

namespace App\Models;

use App\ValueObjects\Address;
use App\ValueObjects\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'status',
        'total_cents',
        'currency',
        'shipping_address',
    ];

    protected $casts = [
        'total_cents' => 'integer',
        'shipping_address' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function total(): Money
    {
        return new Money($this->total_cents, $this->currency);
    }

    public function shippingAddress(): ?Address
    {
        return $this->shipping_address
            ? Address::fromArray($this->shipping_address)
            : null;
    }
}
