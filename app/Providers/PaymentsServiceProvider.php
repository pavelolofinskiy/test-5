<?php

declare(strict_types=1);

namespace App\Providers;

use App\Payments\Contracts\PaymentGateway;
use App\Payments\Gateways\StripeGateway;
use Illuminate\Support\ServiceProvider;
use RuntimeException;

class PaymentsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PaymentGateway::class, function (): PaymentGateway {
            $driver = (string) config('services.payments.driver', 'stripe');

            return match ($driver) {
                'stripe' => new StripeGateway(
                    apiKey: (string) config('services.stripe.secret', ''),
                ),
                default => throw new RuntimeException("Unknown payment driver: {$driver}"),
            };
        });
    }
}
