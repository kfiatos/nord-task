<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Taxes\Application\DTO\ExternalTaxDataResultItem;
use Taxes\Application\DTO\TaxLocationDto;
use Taxes\Domain\TaxType;
use Taxes\Domain\ValueObject\Country;
use Taxes\Domain\ValueObject\CountryState;
use Taxes\Domain\ValueObject\TaxPercentage;
use Taxes\Infrastructure\TaxProvider\TaxBee\TaxBeeProvider;

class TaxBeeProviderTest extends TestCase
{
    /**
     * @dataProvider provideCountryAndStateData
     */
    public function testProvideValidDataForCountryAndState(string $country, string $stateName, array $taxList): void
    {
        $provider = new TaxBeeProvider();

        $location = new TaxLocationDto(new Country($country), new CountryState($stateName));
        $data = $provider->provide($location);
        $expectedCount = count($taxList);

        $this->assertCount($expectedCount, $data);

        /* @var ExternalTaxDataResultItem $singleItem */
        foreach ($taxList as $testCase) {
            foreach ($data as $singleItem) {
                if ($singleItem->type->value === $testCase['taxType']) {
                    $this->assertEquals(new TaxPercentage($testCase['taxPercentage']), $singleItem->percentage);
                    $this->assertEquals(TaxType::from($testCase['taxType']), $singleItem->type);
                }
            }
        }
    }

    public static function provideCountryAndStateData(): \Generator
    {
        yield ['US', 'california', [['taxType' => TaxType::VAT->value, 'taxPercentage' => 7.25]]];
        yield ['CA', 'quebec', [
            ['taxType' => TaxType::GST_HST->value, 'taxPercentage' => 5.0],
            ['taxType' => TaxType::PST->value, 'taxPercentage' => 9.975],
        ]];
        yield ['CA', 'ontario', [['taxType' => TaxType::GST_HST->value, 'taxPercentage' => 13.0]]];
        yield ['CA', 'other_state', [['taxType' => TaxType::VAT->value, 'taxPercentage' => 20.0]]];
        yield ['US', 'other_state', [['taxType' => TaxType::VAT->value, 'taxPercentage' => 20.0]]];
    }
}
