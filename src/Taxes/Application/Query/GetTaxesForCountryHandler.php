<?php

declare(strict_types=1);

namespace App\Taxes\Application\Query;

use App\Shared\Infrastructure\QueryBus\QueryHandlerInterface;
use App\Shared\Infrastructure\QueryBus\QueryInterface;
use App\Taxes\Domain\TaxProviderInterface;

final readonly class GetTaxesForCountryHandler implements QueryHandlerInterface
{
    public function __construct(private readonly TaxProviderInterface $taxProvider)
    {
    }

    public function handle(QueryInterface $query): mixed
    {
        $provider = $this->taxProvider->provide($query->taxLocation);
        $data = $provider->provide($query->taxLocation);

        return [];
    }

    //    public function handle(): mixed
    //    {
    //        $provider = $this->taxProvider->provide($query->taxLocation);
    //        $data = $provider->provide($query->taxLocation);
    //        return [];
    //
    //    }
}
