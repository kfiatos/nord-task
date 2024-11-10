<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Taxes\Domain\Exception\DomainException;
use App\Taxes\Domain\ValueObject\TaxPercentage;
use PHPUnit\Framework\TestCase;

class TestTaxPercentage extends TestCase
{
    public function testItThrowsExceptionWhenInvalidData(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Percentage range out of allowed values');
        TaxPercentage::fromFloat(-1);
    }
}
