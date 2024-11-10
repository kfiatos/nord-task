<?php

declare(strict_types=1);

namespace App\Taxes\Domain;

use App\Taxes\Domain\ValueObject\TaxDataResultInterface;
use App\Taxes\Domain\ValueObject\TaxLocation;

interface TaxProviderInterface
{
    /**
     * @return array<TaxDataResultInterface>
     */
    public function provide(TaxLocation $location): array;
}
