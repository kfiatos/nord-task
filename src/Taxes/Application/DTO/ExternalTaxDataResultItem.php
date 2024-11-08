<?php

declare(strict_types=1);

namespace App\Taxes\Application\DTO;

use App\Taxes\Domain\TaxType;
use App\Taxes\Domain\ValueObject\TaxPercentage;

readonly class ExternalTaxDataResultItem
{
    public function __construct(public TaxType $type, public TaxPercentage $percentage)
    {
    }

    public function getTaxType(): string
    {
        return $this->type->value;
    }

    public function getPercentage(): float
    {
        return $this->percentage->value;
    }
}
