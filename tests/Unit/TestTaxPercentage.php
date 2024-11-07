<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Taxes\Domain\Exception\DomainException;
use Taxes\Domain\ValueObject\TaxPercentage;

class TestTaxPercentage extends TestCase
{
    public function testItThrowsExceptionWhenInvalidData(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Percentage range out of allowed values');
        new TaxPercentage(-1);
    }
}
