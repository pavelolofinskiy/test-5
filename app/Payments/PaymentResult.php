<?php

declare(strict_types=1);

namespace App\Payments;

final readonly class PaymentResult
{
    public function __construct(
        public bool $successful,
        public string $gatewayReference,
        public string $status,
        public ?string $checkoutUrl = null,
        public ?string $errorMessage = null,
    ) {
    }

    public static function success(string $reference, string $status, ?string $checkoutUrl = null): self
    {
        return new self(true, $reference, $status, $checkoutUrl, null);
    }

    public static function failure(string $reference, string $status, string $error): self
    {
        return new self(false, $reference, $status, null, $error);
    }
}
