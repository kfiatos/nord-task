<?php

declare(strict_types=1);

namespace Taxes\Domain\ValueObject;

use Taxes\Domain\TaxType;

readonly class Tax
{
    public function __construct(private TaxType $type, private int $percentage)
    {
    }

    public function getType(): TaxType
    {
        return $this->type;
    }

    public function getPercentage(): float
    {
        return $this->percentage;
    }
}
