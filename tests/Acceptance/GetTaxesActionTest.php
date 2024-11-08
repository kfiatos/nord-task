<?php

declare(strict_types=1);

namespace App\Tests\Acceptance;

use App\Taxes\Domain\TaxType;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetTaxesActionTest extends WebTestCase
{
    /**
     * @dataProvider  provideDataForSupportedCountries
     */
    public function testReturnsValidTaxValuesForSupportedCountries(string $country, TaxType $taxType, float $taxPercentage)
    {
        $client = static::createClient();
        $client->request('GET', sprintf('/taxes?%s', http_build_query(['country' => $country])));
        $response = $client->getResponse();

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(TaxType::VAT->value, current($arrayData)['taxType']);
        $this->assertEquals($taxPercentage, current($arrayData)['percentage']);
    }

    public function testReturnsErrorForInvalidCountryRequest()
    {
        $client = static::createClient();
        $client->request('GET', sprintf('/taxes'));
        $response = $client->getResponse();

        $error = $response->getContent();
        $this->assertStringContainsString('country: This value should not be blank.', $error);
        $this->assertStringContainsString('country: This value should have exactly 2 characters.', $error);
    }

    public function testReturnsErrorForInvalidStateRequest()
    {
        $client = static::createClient();
        $client->request('GET', sprintf('/taxes?country=US&state='));
        $response = $client->getResponse();

        $error = $response->getContent();
        $this->assertStringContainsString('state: This value should not be blank.', $error);
    }

    /**
     * @dataProvider  provideDataForSupportedCountriesWithStates
     */
    public function testReturnsValidTaxValuesForSupportedCountriesWithStates(string $country, string $state, array $taxDataList)
    {
        $client = static::createClient();
        $client->request('GET', sprintf('/taxes?%s', http_build_query(['country' => $country, 'state' => $state])));
        $response = $client->getResponse();

        $resultArray = json_decode($response->getContent(), true);
        foreach ($taxDataList as $singleTaxData) {
            foreach ($resultArray as $singleResult) {
                if ($singleTaxData['taxType'] === $singleResult['taxType']) {
                    $this->assertEquals($singleTaxData['percentage'], $singleResult['percentage']);
                }
            }
        }
    }

    public static function provideDataForSupportedCountries(): \Generator
    {
        yield ['DE', TaxType::VAT, 19.0];
        yield ['LT', TaxType::VAT, 21.0];
        yield ['LV', TaxType::VAT, 22.0];
        yield ['EE', TaxType::VAT, 20.0];
    }

    public static function provideDataForSupportedCountriesWithStates(): \Generator
    {
        yield ['US', 'other_state', [['taxType' => TaxType::VAT->value, 'percentage' => 20.0]]];
        yield ['US', 'california', [['taxType' => TaxType::VAT->value, 'percentage' => 7.25]]];
        yield ['CA', 'quebec', [['taxType' => TaxType::GST_HST->value, 'percentage' => 5.0], ['taxType' => TaxType::PST, 'percentage' => 9.975]]];
        yield ['CA', 'ontario', [['taxType' => TaxType::GST_HST->value, 'percentage' => 13.0]]];
        yield ['CA', 'other', [['taxType' => TaxType::VAT->value, 'percentage' => 20.0]]];
    }
}
