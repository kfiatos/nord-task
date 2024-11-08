<?php

declare(strict_types=1);

namespace App\Taxes\Application\Handler\Handler;

use App\Taxes\Application\DTO\ExternalTaxDataResultItem;
use App\Taxes\Application\Query\GetTaxesForCountryQuery;
use App\Taxes\Application\ReadModel\TaxReadModel;
use App\Taxes\Domain\TaxProviderInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetTaxesForCountryHandler
{
    public function __construct(private TaxProviderInterface $taxProvider)
    {
    }

    public function __invoke(GetTaxesForCountryQuery $query): mixed
    {
        /** @var ExternalTaxDataResultItem[] $result */
        $result = $this->taxProvider->provide($query->taxLocation);

        return array_map(function ($row) {
            return new TaxReadModel($row->type->value, $row->percentage->value);
        }, $result);
    }
}
