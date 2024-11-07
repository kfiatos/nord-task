<?php

declare(strict_types=1);

namespace App\Taxes\Application;

use App\Taxes\Domain\ValueObject\TaxLocation;

interface TaxDataProviderInterface
{
    public function provide(TaxLocation $taxLocation): array;

    public function supports(TaxLocation $taxLocationDto): bool;
}
