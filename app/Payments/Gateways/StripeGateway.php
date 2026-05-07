<?php

declare(strict_types=1);

namespace App\Payments\Gateways;

use App\Models\Order;
use App\Payments\Contracts\PaymentGateway;
use App\Payments\PaymentResult;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class StripeGateway implements PaymentGateway
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $apiBase = 'https://api.stripe.com/v1',
    ) {
    }

    public function charge(Order $order, array $options = []): PaymentResult
    {
        try {
            $response = Http::asForm()
                ->withToken($this->apiKey)
                ->post("{$this->apiBase}/payment_intents", [
                    'amount' => $order->total_cents,
                    'currency' => strtolower($order->currency),
                    'metadata[order_id]' => (string) $order->id,
                    'automatic_payment_methods[enabled]' => 'true',
                ])
                ->throw()
                ->json();

            return PaymentResult::success(
                reference: (string) $response['id'],
                status: (string) $response['status'],
                checkoutUrl: $response['next_action']['redirect_to_url']['url'] ?? null,
            );
        } catch (Throwable $e) {
            return PaymentResult::failure(
                reference: 'pi_failed_'.Str::random(12),
                status: 'failed',
                error: $e->getMessage(),
            );
        }
    }

    public function refund(string $gatewayReference, int $amountCents): PaymentResult
    {
        try {
            $response = Http::asForm()
                ->withToken($this->apiKey)
                ->post("{$this->apiBase}/refunds", [
                    'payment_intent' => $gatewayReference,
                    'amount' => $amountCents,
                ])
                ->throw()
                ->json();

            return PaymentResult::success(
                reference: (string) $response['id'],
                status: (string) $response['status'],
            );
        } catch (Throwable $e) {
            return PaymentResult::failure(
                reference: 'rf_failed_'.Str::random(12),
                status: 'failed',
                error: $e->getMessage(),
            );
        }
    }
}
