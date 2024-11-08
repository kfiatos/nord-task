<?php

declare(strict_types=1);

namespace App\Taxes\Infrastructure\TaxProvider\SeriousTax;

use App\ExternalService\SeriousTax\Location;
use App\ExternalService\SeriousTax\SeriousTaxService;
use App\ExternalService\SeriousTax\TimeoutException;
use App\Taxes\Application\DTO\ExternalTaxDataResultItem;
use App\Taxes\Application\TaxDataProviderInterface;
use App\Taxes\Domain\Exception\DomainException;
use App\Taxes\Domain\TaxType;
use App\Taxes\Domain\ValueObject\TaxLocation;
use App\Taxes\Domain\ValueObject\TaxPercentage;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem('app.tax_provider')]
class SeriousTaxProvider implements TaxDataProviderInterface
{
    private const array SUPPORTED_COUNTRIES = ['DE', 'LT', 'LV', 'EE', 'PL'];

    private const TaxType TAX_TYPE = TaxType::VAT;

    /**
     * @throws ExternalProviderException
     * @throws DomainException
     */
    public function provide(TaxLocation $taxLocation): array
    {
        $location = new Location($taxLocation->country->countryCode, null);
        try {
            $result = (new SeriousTaxService())->getTaxesResult($location);

            return
                [
                    new ExternalTaxDataResultItem(
                        TaxType::from(self::TAX_TYPE->value),
                        new TaxPercentage($result)
                    ),
                ];
        } catch (TimeoutException) {
            throw new ExternalProviderException(sprintf('Error: Could not retrieve data for country: %s', $taxLocation->country->countryCode));
        }
    }

    public function supports(TaxLocation $taxLocationDto): bool
    {
        return in_array($taxLocationDto->country->countryCode, self::SUPPORTED_COUNTRIES);
    }
}
