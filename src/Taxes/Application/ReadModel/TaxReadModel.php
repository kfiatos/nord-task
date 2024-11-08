<?php

declare(strict_types=1);

namespace App\Taxes\Application\ReadModel;

final readonly class TaxReadModel
{
    public function __construct(public string $taxType, public float $percentage)
    {
    }
}
