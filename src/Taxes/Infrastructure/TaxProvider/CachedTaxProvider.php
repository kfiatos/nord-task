<?php

declare(strict_types=1);

namespace App\Taxes\Infrastructure\TaxProvider;

use App\Taxes\Domain\TaxProviderInterface;
use App\Taxes\Domain\ValueObject\TaxLocation;

class CachedTaxProvider implements TaxProviderInterface
{
    public function __construct(private TaxProvider $provider)
    {
    }

    public function provide(TaxLocation $location)
    {
        // zrob klucz
        // this cache poll get klucz
        // jak neie ma w cache to zrob
        return $this->provider->provide($location);
        // zapisz do cache pod kluczem i dopiero return
    }
}
