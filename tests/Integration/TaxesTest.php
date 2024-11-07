<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaxesTest extends WebTestCase
{
    public function testGetTaxes(): void
    {
        $client = static::createClient();

        $client->request('GET', '/taxes');

        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $this->assertEquals(json_encode(['message' => 'implement me']), $response->getContent());
    }
}
