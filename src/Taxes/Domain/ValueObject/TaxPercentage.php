<?php

declare(strict_types=1);

namespace App\Taxes\Domain\ValueObject;

use App\Taxes\Domain\Exception\DomainException;

final readonly class TaxPercentage
{
    public function __construct(private float $value)
    {
        if ($this->value < 0) {
            throw new DomainException('Percentage range out of allowed values');
        }
    }
}
