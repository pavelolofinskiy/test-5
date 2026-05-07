<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\ValueObjects\Address;
use App\ValueObjects\Money;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class OrderService
{
    /**
     * @param  array<int, array{product_id: int, quantity: int}>  $items
     * @param  array{line1:string,line2:?string,city:string,state:?string,postal_code:string,country:string}  $shippingAddress
     */
    public function create(User $user, array $items, array $shippingAddress): Order
    {
        $address = Address::fromArray($shippingAddress);

        return DB::transaction(function () use ($user, $items, $address) {
            $products = Product::whereIn('id', array_column($items, 'product_id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $currency = null;
            $total = null;
            $rows = [];

            foreach ($items as $item) {
                $product = $products[$item['product_id']] ?? null;
                if (! $product) {
                    throw new RuntimeException("Product {$item['product_id']} not found.");
                }
                if ($product->stock < $item['quantity']) {
                    throw new RuntimeException("Insufficient stock for product {$product->id}.");
                }

                $unitPrice = $product->price();
                $currency ??= $unitPrice->currency;
                $lineTotal = $unitPrice->multiply($item['quantity']);
                $total = $total ? $total->add($lineTotal) : $lineTotal;

                $rows[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price_cents' => $unitPrice->cents,
                    'currency' => $unitPrice->currency,
                ];

                $product->decrement('stock', $item['quantity']);
            }

            /** @var Money $total */
            $order = $user->orders()->create([
                'status' => Order::STATUS_PENDING,
                'total_cents' => $total->cents,
                'currency' => $total->currency,
                'shipping_address' => $address->toArray(),
            ]);

            foreach ($rows as $row) {
                $order->items()->create($row);
            }

            return $order;
        });
    }
}
