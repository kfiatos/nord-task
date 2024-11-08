<?php

declare(strict_types=1);

namespace App\Taxes\Domain\ValueObject;

use App\Taxes\Domain\TaxType;

final readonly class Tax
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
