<?php

declare(strict_types=1);

namespace Taxes\Application\DTO;

use Taxes\Domain\TaxType;
use Taxes\Domain\ValueObject\Country;
use Taxes\Domain\ValueObject\CountryState;
use Taxes\Domain\ValueObject\TaxPercentage;

readonly class ExternalTaxDataResultItem
{
    public function __construct(public Country $country, public TaxType $type, public TaxPercentage $percentage, public ?CountryState $state = null)
    {
    }
}
