<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Taxes\Application\DTO\ExternalTaxDataResultItem;
use App\Taxes\Domain\Exception\NoMatchingProviderException;
use App\Taxes\Domain\TaxType;
use App\Taxes\Domain\ValueObject\Country;
use App\Taxes\Domain\ValueObject\TaxLocation;
use App\Taxes\Domain\ValueObject\TaxPercentage;
use App\Taxes\Infrastructure\TaxProvider\SeriousTax\ExternalProviderException;
use App\Taxes\Infrastructure\TaxProvider\SeriousTax\SeriousTaxProvider;
use App\Taxes\Infrastructure\TaxProvider\TaxProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaxProviderTest extends KernelTestCase
{
    /**
     * @dataProvider  provideValidCountriesForSeriousTaxProvider
     */
    public function testReturnsValidData(string $country, TaxType $taxType, float $percentage)
    {
        self::bootKernel();

        $container = static::getContainer();
        $providers = $container->get(SeriousTaxProvider::class);

        $taxProvider = new TaxProvider([$providers]);

        $result = $taxProvider->provide(new TaxLocation(new Country($country), null));

        $this->assertEquals(new ExternalTaxDataResultItem($taxType, new TaxPercentage($percentage)
        ), current($result));
    }

    /**
     * @dataProvider  provideInvalidCountries
     */
    public function testReturnsErrorForInvalidCountry(string $country, string $errorMsg, string $exceptionClass)
    {
        self::bootKernel();

        $container = static::getContainer();
        $providers = $container->get(SeriousTaxProvider::class);

        $taxProvider = new TaxProvider([$providers]);
        $this->expectException($exceptionClass);
        $this->expectExceptionMessage($errorMsg);
        $taxProvider->provide(new TaxLocation(new Country($country), null));
    }

    public static function provideInvalidCountries()
    {
        yield ['PL', 'Error: Could not retrieve data for country: PL', ExternalProviderException::class];
        yield ['IE', 'No matching provider for given location', NoMatchingProviderException::class];
    }

    public static function provideValidCountriesForSeriousTaxProvider()
    {
        yield ['DE', TaxType::VAT, 19.0];
        yield ['LV', TaxType::VAT, 22.0];
        yield ['LT', TaxType::VAT, 21.0];
        yield ['EE', TaxType::VAT, 20.0];
    }
}
