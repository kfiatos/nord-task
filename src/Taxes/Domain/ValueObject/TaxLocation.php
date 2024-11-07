<?php

declare(strict_types=1);

namespace App\Taxes\Domain\ValueObject;

readonly class TaxLocation
{
    public function __construct(public Country $country, public ?CountryState $state)
    {
    }
}
