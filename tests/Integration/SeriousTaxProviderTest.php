<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Taxes\Application\DTO\ExternalTaxDataResultItem;
use App\Taxes\Domain\TaxType;
use App\Taxes\Domain\ValueObject\Country;
use App\Taxes\Domain\ValueObject\TaxLocation;
use App\Taxes\Domain\ValueObject\TaxPercentage;
use App\Taxes\Infrastructure\TaxProvider\SeriousTax\ExternalProviderException;
use App\Taxes\Infrastructure\TaxProvider\SeriousTax\SeriousTaxProvider;
use PHPUnit\Framework\TestCase;

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
        /** @var ExternalTaxDataResultItem $firstResultItem */
        $firstResultItem = current($data);

        $this->assertEquals(TaxPercentage::fromFloat($percentage), $firstResultItem->percentage);
        $this->assertEquals(TaxType::VAT, $firstResultItem->type);
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
