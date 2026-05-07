<?php

declare(strict_types=1);

namespace App\ValueObjects;

use InvalidArgumentException;

final readonly class Money
{
    public function __construct(
        public int $cents,
        public string $currency = 'USD',
    ) {
        if ($cents < 0) {
            throw new InvalidArgumentException('Money amount must be non-negative.');
        }
        if (strlen($currency) !== 3) {
            throw new InvalidArgumentException('Currency must be a 3-letter ISO 4217 code.');
        }
    }

    public function add(Money $other): self
    {
        $this->guardSameCurrency($other);

        return new self($this->cents + $other->cents, $this->currency);
    }

    public function subtract(Money $other): self
    {
        $this->guardSameCurrency($other);

        return new self($this->cents - $other->cents, $this->currency);
    }

    public function multiply(int $factor): self
    {
        if ($factor < 0) {
            throw new InvalidArgumentException('Factor must be non-negative.');
        }

        return new self($this->cents * $factor, $this->currency);
    }

    public function format(): string
    {
        return number_format($this->cents / 100, 2).' '.$this->currency;
    }

    private function guardSameCurrency(Money $other): void
    {
        if ($this->currency !== $other->currency) {
            throw new InvalidArgumentException(
                "Currency mismatch: {$this->currency} vs {$other->currency}."
            );
        }
    }
}
