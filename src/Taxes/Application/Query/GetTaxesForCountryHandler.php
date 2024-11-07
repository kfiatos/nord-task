<?php

declare(strict_types=1);

namespace Taxes\Application\Query;

use Shared\Infrastructure\QueryBus\QueryHandlerInterface;
use Taxes\Domain\TaxProviderInterface;

final readonly class GetTaxesForCountryHandler implements QueryHandlerInterface
{
    public function __construct(private readonly TaxProviderInterface $taxProvider)
    {
    }

    public function handle(GetTaxesForCountryQuery $query): mixed
    {
        $provider = $this->taxProvider->provide($query->taxLocation);
        $data = $provider->provide($query->taxLocation);


    }
}
