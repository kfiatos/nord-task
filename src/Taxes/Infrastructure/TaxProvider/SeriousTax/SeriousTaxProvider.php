<?php

declare(strict_types=1);

namespace Taxes\Infrastructure\TaxProvider\SeriousTax;

use App\ExternalService\SeriousTax\Location;
use App\ExternalService\SeriousTax\SeriousTaxService;
use App\ExternalService\SeriousTax\TimeoutException;
use Taxes\Application\DTO\ExternalTaxDataResultItem;
use Taxes\Application\DTO\TaxLocationDto;
use Taxes\Application\TaxDataProviderInterface;
use Taxes\Domain\TaxType;
use Taxes\Domain\ValueObject\Country;
use Taxes\Domain\ValueObject\TaxPercentage;

class SeriousTaxProvider implements TaxDataProviderInterface
{
    private const array SUPPORTED_COUNTRIES = ['DE', 'LT', 'LV', 'EE', 'PL'];

    private const TaxType TAX_TYPE = TaxType::VAT;

    public function provide(TaxLocationDto $taxLocation): array
    {
        $location = new Location($taxLocation->country->countryCode, $taxLocation->state->stateName);
        try {
            $result = (new SeriousTaxService())->getTaxesResult($location);

            return
                [
                    new ExternalTaxDataResultItem(
                        new Country($taxLocation->country->countryCode),
                        TaxType::from(self::TAX_TYPE->value),
                        new TaxPercentage($result)
                    ),
                ];
        } catch (TimeoutException) {
            throw new ExternalProviderException(sprintf('Error: Could not retrieve data for country: %s', $taxLocation->country->countryCode));
        }
    }

    public function supports(TaxLocationDto $taxLocationDto): bool
    {
        return in_array($taxLocationDto->country->countryCode, self::SUPPORTED_COUNTRIES);
    }
}
