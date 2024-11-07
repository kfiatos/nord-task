<?php

declare(strict_types=1);

namespace Taxes\Domain;

use Taxes\Domain\ValueObject\Tax;

class CountryTaxes
{
    /**
     * @param Tax[] $taxes
     */
    public function __construct(private array $taxes)
    {
    }

    public function add($tax): void
    {
        $this->taxes[] = $tax;
    }
}
