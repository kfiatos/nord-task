<?php

declare(strict_types=1);

namespace Taxes\Application;

use Taxes\Application\DTO\TaxLocationDto;

interface TaxDataProviderInterface
{
    public function provide(TaxLocationDto $taxLocation): array;

    public function supports(TaxLocationDto $taxLocationDto): bool;
}
