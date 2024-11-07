<?php

declare(strict_types=1);

namespace Taxes\Application\Query;

use Taxes\Domain\ValueObject\TaxLocation;
use Shared\Infrastructure\QueryBus\QueryInterface;

final readonly class GetTaxesForCountryQuery implements QueryInterface
{
    public function __construct(public TaxLocation $taxLocation)
    {
    }
}
