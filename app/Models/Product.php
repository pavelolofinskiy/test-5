<?php

declare(strict_types=1);

namespace App\Models;

use App\ValueObjects\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price_cents',
        'currency',
        'stock',
    ];

    protected $casts = [
        'price_cents' => 'integer',
        'stock' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function price(): Money
    {
        return new Money($this->price_cents, $this->currency);
    }
}
