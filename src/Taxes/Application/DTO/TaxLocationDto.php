<?php

declare(strict_types=1);

namespace App\Taxes\Application\DTO;

use App\Taxes\Domain\ValueObject\Country;
use App\Taxes\Domain\ValueObject\CountryState;

readonly class TaxLocationDto
{
    public function __construct(public Country $country, public ?CountryState $state)
    {
    }
}
