<?php

declare(strict_types=1);

namespace Taxes\Infrastructure\TaxProvider\TaxBee;

use App\ExternalService\TaxBee\TaxBee;
use App\ExternalService\TaxBee\TaxBeeException;
use Taxes\Application\DTO\ExternalTaxDataResultItem;
use Taxes\Application\DTO\TaxLocationDto;
use Taxes\Application\TaxDataProviderInterface;
use Taxes\Domain\TaxType;
use Taxes\Domain\ValueObject\Country;
use Taxes\Domain\ValueObject\CountryState;
use Taxes\Domain\ValueObject\TaxPercentage;

class TaxBeeProvider implements TaxDataProviderInterface
{
    private const array SUPPORTED_COUNTRIES = ['US', 'CA'];

    /**
     * @throws CountryNotSupportedException
     * @throws ExternalProviderException
     */
    public function provide(TaxLocationDto $taxLocation): array
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

    public function supports(TaxLocationDto $taxLocationDto): bool
    {
        return in_array($taxLocationDto->country->countryCode, self::SUPPORTED_COUNTRIES);
    }
}
