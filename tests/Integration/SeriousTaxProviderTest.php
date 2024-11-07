<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Taxes\Domain\TaxType;
use Taxes\Domain\ValueObject\Country;
use Taxes\Domain\ValueObject\TaxLocation;
use Taxes\Domain\ValueObject\TaxPercentage;
use Taxes\Infrastructure\TaxProvider\SeriousTax\ExternalProviderException;
use Taxes\Infrastructure\TaxProvider\SeriousTax\SeriousTaxProvider;

class SeriousTaxProviderTest extends TestCase
{
    /**
     * @dataProvider provideValidCountriesWithTaxRates
     */
    public function testProvideValidDataForCountry(string $country, float $percentage): void
    {
        $provider = new SeriousTaxProvider();

        $location = new TaxLocation(new Country($country), null);

        $data = $provider->provide($location);
        reset($data);

        $this->assertEquals(new TaxPercentage($percentage), current($data)->percentage);
        $this->assertEquals(TaxType::VAT, current($data)->type);
    }

    public function testTestThrowsExceptionForInvalidCountry(): void
    {
        $provider = new SeriousTaxProvider();

        $location = new TaxLocation(new Country('PL'), null);

        $this->expectException(ExternalProviderException::class);
        $provider->provide($location);
    }

    public static function provideValidCountriesWithTaxRates(): \Generator
    {
        yield ['DE', 19.0];
        yield ['LT', 21.0];
        yield ['LV', 22.0];
        yield ['EE', 20.0];
    }
}
