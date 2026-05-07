<?php

declare(strict_types=1);

namespace App\ValueObjects;

use InvalidArgumentException;

final readonly class Address
{
    public function __construct(
        public string $line1,
        public string $city,
        public string $postalCode,
        public string $country,
        public ?string $line2 = null,
        public ?string $state = null,
    ) {
        if ($line1 === '' || $city === '' || $postalCode === '' || $country === '') {
            throw new InvalidArgumentException('Address fields line1/city/postalCode/country are required.');
        }
        if (strlen($country) !== 2) {
            throw new InvalidArgumentException('Country must be a 2-letter ISO 3166-1 alpha-2 code.');
        }
    }

    /**
     * @param  array{line1: string, city: string, postal_code: string, country: string, line2?: ?string, state?: ?string}  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            line1: $data['line1'],
            city: $data['city'],
            postalCode: $data['postal_code'],
            country: $data['country'],
            line2: $data['line2'] ?? null,
            state: $data['state'] ?? null,
        );
    }

    /**
     * @return array{line1: string, line2: ?string, city: string, state: ?string, postal_code: string, country: string}
     */
    public function toArray(): array
    {
        return [
            'line1' => $this->line1,
            'line2' => $this->line2,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postalCode,
            'country' => $this->country,
        ];
    }

    public function singleLine(): string
    {
        $parts = array_filter([
            $this->line1,
            $this->line2,
            $this->city,
            $this->state,
            $this->postalCode,
            $this->country,
        ]);

        return implode(', ', $parts);
    }
}
