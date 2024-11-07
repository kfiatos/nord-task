<?php

declare(strict_types=1);

namespace Taxes\Domain;

use App\Taxes\Domain\ValueObject\TaxLocation;

interface TaxProviderInterface
{
    public function provide(TaxLocation $location);
}
