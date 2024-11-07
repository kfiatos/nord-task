<?php

declare(strict_types=1);

namespace App\Taxes\Domain\ValueObject;

final readonly class Country
{
    public function __construct(public string $countryCode)
    {
    }
}
