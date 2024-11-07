<?php

declare(strict_types=1);

namespace Taxes\Application\DTO;

use Taxes\Domain\ValueObject\Country;
use Taxes\Domain\ValueObject\CountryState;

readonly class TaxLocationDto
{
    public function __construct(public Country $country, public ?CountryState $state)
    {
    }
}
