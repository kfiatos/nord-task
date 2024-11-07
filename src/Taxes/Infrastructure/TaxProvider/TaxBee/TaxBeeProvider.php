<?php

declare(strict_types=1);

namespace App\Taxes\Infrastructure\TaxProvider\TaxBee;

use App\ExternalService\TaxBee\TaxBee;
use App\ExternalService\TaxBee\TaxBeeException;
use App\Taxes\Application\DTO\ExternalTaxDataResultItem;
use App\Taxes\Application\TaxDataProviderInterface;
use App\Taxes\Domain\TaxType;
use App\Taxes\Domain\ValueObject\Country;
use App\Taxes\Domain\ValueObject\CountryState;
use App\Taxes\Domain\ValueObject\TaxLocation;
use App\Taxes\Domain\ValueObject\TaxPercentage;

class TaxBeeProvider implements TaxDataProviderInterface
{
    private const array SUPPORTED_COUNTRIES = ['US', 'CA'];

    /**
     * @throws CountryNotSupportedException
     * @throws ExternalProviderException
     */
    public function provide(TaxLocation $taxLocation): array
    {
        $client = new TaxBee();
        try {
            $externalData = $client->getTaxes(country: $taxLocation->country->countryCode, state: $taxLocation->state->stateName, city: '', street: '', postcode: '');

            return array_map(function ($row) use ($taxLocation) {
                return new ExternalTaxDataResultItem(
                    country: new Country($taxLocation->country->countryCode),
                    type: TaxType::from($row->type->value),
                    percentage: new TaxPercentage($row->taxPercentage),
                    state: new CountryState($taxLocation->state->stateName)
                );
            }, $externalData);
        } catch (TaxBeeException $taxBeeException) {
            throw new ExternalProviderException(previous: $taxBeeException);
        }
    }

    public function supports(TaxLocation $taxLocationDto): bool
    {
        return in_array($taxLocationDto->country->countryCode, self::SUPPORTED_COUNTRIES);
    }
}
