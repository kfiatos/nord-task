<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function testSmth(): void
    {
        $x = 1;
        $this->assertEquals(1, $x);
    }
}
