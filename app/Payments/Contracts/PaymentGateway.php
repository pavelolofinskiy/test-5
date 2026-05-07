<?php

declare(strict_types=1);

namespace App\Payments\Contracts;

use App\Models\Order;
use App\Payments\PaymentResult;

interface PaymentGateway
{
    /**
     * Create a payment intent / charge for the given order.
     *
     * @param  array<string, mixed>  $options  Gateway-specific options (e.g. 'return_url' for redirects).
     */
    public function charge(Order $order, array $options = []): PaymentResult;

    /**
     * Refund a previously successful charge by its gateway reference id.
     */
    public function refund(string $gatewayReference, int $amountCents): PaymentResult;
}
