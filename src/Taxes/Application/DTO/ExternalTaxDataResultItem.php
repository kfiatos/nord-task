<?php

declare(strict_types=1);

namespace App\Taxes\Application\DTO;

use App\Taxes\Domain\TaxType;
use App\Taxes\Domain\ValueObject\Country;
use App\Taxes\Domain\ValueObject\CountryState;
use App\Taxes\Domain\ValueObject\TaxPercentage;

readonly class ExternalTaxDataResultItem
{
    public function __construct(public Country $country, public TaxType $type, public TaxPercentage $percentage, public ?CountryState $state = null)
    {
    }
}
