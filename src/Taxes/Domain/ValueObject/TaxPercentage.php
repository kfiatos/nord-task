<?php

declare(strict_types=1);

namespace App\Taxes\Domain\ValueObject;

use App\Taxes\Domain\Exception\DomainException;

final readonly class TaxPercentage
{
    private function __construct(public float $value)
    {
        if ($this->value < 0) {
            throw new DomainException('Percentage range out of allowed values');
        }
    }

    public static function fromFloat(float $value): self
    {
        return new self($value);
    }
}
