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
    public function testReturnsValidTaxValuesForSupportedCountries(string $country, TaxType $taxType, float $taxPercentage): void
    {
        $client = static::createClient();
        $client->request('GET', sprintf('/taxes?%s', http_build_query(['country' => $country])));

        /** @var string $responseContent */
        $responseContent = $client->getResponse()->getContent();

        /** @var array<string> $arrayData */
        $arrayData = json_decode($responseContent, true);
        reset($arrayData);

        /** @var array<string> $fistResponseElement */
        $fistResponseElement = current($arrayData);

        $this->assertEquals(TaxType::VAT->value, $fistResponseElement['taxType']);
        $this->assertEquals($taxPercentage, $fistResponseElement['percentage']);
    }

    public function testReturnsErrorForInvalidCountryRequest(): void
    {
        $client = static::createClient();
        $client->request('GET', sprintf('/taxes'));
        $response = $client->getResponse();

        /** @var string $error */
        $error = $response->getContent();
        $this->assertStringContainsString('country: This value should not be blank.', $error);
        $this->assertStringContainsString('country: This value should have exactly 2 characters.', $error);
    }

    public function testReturnsErrorForCountryWhereExternalProviderFails(): void
    {
        $client = static::createClient();
        $client->request('GET', sprintf('/taxes?country=PL'));
        $response = $client->getResponse();

        /** @var string $responseContent */
        $responseContent = $response->getContent();
        /** @var array<string, string> $errorResponse */
        $errorResponse = json_decode($responseContent, true);
        $this->assertArrayHasKey('message', $errorResponse);
        $this->assertStringContainsString('Failed fetching taxes for country: PL', $errorResponse['message']);
    }

    public function testReturnsErrorForInvalidStateRequest(): void
    {
        $client = static::createClient();
        $client->request('GET', sprintf('/taxes?country=US&state='));
        $response = $client->getResponse();

        /** @var string $error */
        $error = $response->getContent();
        $this->assertStringContainsString('state: This value should not be blank.', $error);
    }

    /**
     * @param array<array<string>> $taxDataList
     *
     * @dataProvider  provideDataForSupportedCountriesWithStates
     */
    public function testReturnsValidTaxValuesForSupportedCountriesWithStates(string $country, string $state, array $taxDataList): void
    {
        $client = static::createClient();
        $client->request('GET', sprintf('/taxes?%s', http_build_query(['country' => $country, 'state' => $state])));

        /** @var string $responseContent */
        $responseContent = $client->getResponse()->getContent();
        /** @var array<array<string>> $resultArray */
        $resultArray = json_decode($responseContent, true);
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
