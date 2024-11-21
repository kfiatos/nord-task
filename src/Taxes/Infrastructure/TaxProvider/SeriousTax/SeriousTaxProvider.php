<?php

declare(strict_types=1);

namespace App\Taxes\Infrastructure\TaxProvider\SeriousTax;

use App\ExternalService\SeriousTax\Location;
use App\ExternalService\SeriousTax\SeriousTaxService;
use App\ExternalService\SeriousTax\TimeoutException;
use App\Taxes\Application\DTO\ExternalTaxDataResultItem;
use App\Taxes\Application\TaxDataProviderInterface;
use App\Taxes\Domain\TaxType;
use App\Taxes\Domain\ValueObject\TaxLocation;
use App\Taxes\Domain\ValueObject\TaxPercentage;
use App\Taxes\Infrastructure\TaxProvider\Exception\ExternalProviderException;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem('app.tax_provider')]
class SeriousTaxProvider implements TaxDataProviderInterface
{
    public function __construct(private readonly SeriousTaxService $taxProvider)
    {
    }

    private const array SUPPORTED_COUNTRIES = ['DE', 'LT', 'LV', 'EE', 'PL', 'BR'];

    private const TaxType TAX_TYPE = TaxType::VAT;

    /**
     * @throws ExternalProviderException
     */
    public function provide(TaxLocation $taxLocation): array
    {
        $location = new Location($taxLocation->country->countryCode, null);
        try {
            $result = $this->taxProvider->getTaxesResult($location);

            return
                [
                    new ExternalTaxDataResultItem(
                        TaxType::from(self::TAX_TYPE->value),
                        TaxPercentage::fromFloat($result)
                    ),
                ];
        } catch (TimeoutException) {
            throw new ExternalProviderException(sprintf('Failed fetching taxes for country: %s', $taxLocation->country->countryCode));
        }
    }

    public function supports(TaxLocation $taxLocationDto): bool
    {
        return in_array($taxLocationDto->country->countryCode, self::SUPPORTED_COUNTRIES);
    }
}
