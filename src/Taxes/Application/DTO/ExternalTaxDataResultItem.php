<?php

declare(strict_types=1);

namespace App\Taxes\Application\DTO;

use App\Taxes\Domain\TaxType;
use App\Taxes\Domain\ValueObject\TaxDataResultInterface;
use App\Taxes\Domain\ValueObject\TaxPercentage;

readonly class ExternalTaxDataResultItem implements TaxDataResultInterface
{
    public function __construct(public TaxType $type, public TaxPercentage $percentage)
    {
    }
}
