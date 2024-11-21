<?php

declare(strict_types=1);

namespace App\Taxes\Infrastructure\TaxProvider\TaxBee;

use App\ExternalService\TaxBee\TaxBee;
use App\ExternalService\TaxBee\TaxBeeException;
use App\Taxes\Application\DTO\ExternalTaxDataResultItem;
use App\Taxes\Application\TaxDataProviderInterface;
use App\Taxes\Domain\TaxType;
use App\Taxes\Domain\ValueObject\TaxLocation;
use App\Taxes\Domain\ValueObject\TaxPercentage;
use App\Taxes\Infrastructure\TaxProvider\Exception\ExternalProviderException;

class TaxBeeProvider implements TaxDataProviderInterface
{
    public function __construct(private readonly TaxBee $taxProvider)
    {
    }

    private const array SUPPORTED_COUNTRIES = ['US', 'CA'];

    /**
     * @throws ExternalProviderException
     */
    public function provide(TaxLocation $taxLocation): array
    {
        try {
            $externalData = $this->taxProvider->getTaxes(country: $taxLocation->country->countryCode, state: $taxLocation->state->stateName ?? '', city: '', street: '', postcode: '');

            return array_map(function ($row) {
                return new ExternalTaxDataResultItem(
                    type: TaxType::from($row->type->value),
                    percentage: TaxPercentage::fromFloat($row->taxPercentage),
                );
            }, $externalData);
        } catch (TaxBeeException $taxBeeException) {
            throw new ExternalProviderException(previous: $taxBeeException);
        }
    }

    public function supports(TaxLocation $taxLocationDto): bool
    {
        return in_array($taxLocationDto->country->countryCode, self::SUPPORTED_COUNTRIES) && (null != $taxLocationDto->state);
    }
}
