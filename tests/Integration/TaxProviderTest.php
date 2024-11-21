<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Taxes\Application\DTO\ExternalTaxDataResultItem;
use App\Taxes\Application\TaxDataProviderInterface;
use App\Taxes\Domain\Exception\NoMatchingProviderException;
use App\Taxes\Domain\TaxType;
use App\Taxes\Domain\ValueObject\Country;
use App\Taxes\Domain\ValueObject\TaxLocation;
use App\Taxes\Domain\ValueObject\TaxPercentage;
use App\Taxes\Infrastructure\TaxProvider\Exception\ExternalProviderException;
use App\Taxes\Infrastructure\TaxProvider\SeriousTax\SeriousTaxProvider;
use App\Taxes\Infrastructure\TaxProvider\TaxProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaxProviderTest extends KernelTestCase
{
    /**
     * @dataProvider  provideValidCountriesForSeriousTaxProvider
     */
    public function testReturnsValidData(string $country, TaxType $taxType, float $percentage): void
    {
        self::bootKernel();

        $container = static::getContainer();
        $providers = $container->get(SeriousTaxProvider::class);

        /** @var iterable<TaxDataProviderInterface> $providersList */
        $providersList = [$providers];

        $taxProvider = new TaxProvider($providersList);

        $result = $taxProvider->provide(new TaxLocation(new Country($country), null));

        $this->assertEquals(new ExternalTaxDataResultItem($taxType, TaxPercentage::fromFloat($percentage)
        ), current($result));
    }

    /**
     * @dataProvider  provideInvalidCountries
     */
    public function testReturnsErrorForInvalidCountry(string $country, string $errorMsg, string $exceptionClass): void
    {
        self::bootKernel();

        $container = static::getContainer();
        $providers = $container->get(SeriousTaxProvider::class);

        /** @var iterable<TaxDataProviderInterface> $providersList */
        $providersList = [$providers];
        $taxProvider = new TaxProvider($providersList);
        /* @phpstan-ignore-next-line */
        $this->expectException($exceptionClass);
        $this->expectExceptionMessage($errorMsg);
        $taxProvider->provide(new TaxLocation(new Country($country), null));
    }

    public static function provideInvalidCountries(): \Generator
    {
        yield ['PL', 'Failed fetching taxes for country: PL', ExternalProviderException::class];
        yield ['IE', 'No matching provider for given location', NoMatchingProviderException::class];
    }

    public static function provideValidCountriesForSeriousTaxProvider(): \Generator
    {
        yield ['DE', TaxType::VAT, 19.0];
        yield ['LV', TaxType::VAT, 22.0];
        yield ['LT', TaxType::VAT, 21.0];
        yield ['EE', TaxType::VAT, 20.0];
    }
}
