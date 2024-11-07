<?php

declare(strict_types=1);

namespace App\Taxes\Application\Query;

use App\Shared\Infrastructure\QueryBus\QueryInterface;
use App\Taxes\Domain\ValueObject\TaxLocation;

class GetTaxesForCountryQuery implements QueryInterface
{
    public function __construct(public TaxLocation $taxLocation)
    {
    }
}
