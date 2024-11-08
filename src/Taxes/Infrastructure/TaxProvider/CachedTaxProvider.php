<?php

declare(strict_types=1);

namespace App\Taxes\Infrastructure\TaxProvider;

use App\Taxes\Domain\TaxProviderInterface;
use App\Taxes\Domain\ValueObject\TaxLocation;
use Symfony\Component\Cache\CacheItem;
use Symfony\Contracts\Cache\CacheInterface;

class CachedTaxProvider implements TaxProviderInterface
{
    public function __construct(private TaxProvider $provider, private CacheInterface $cache, private int $ttl)
    {
    }

    public function provide(TaxLocation $location)
    {
        $cacheKey = $this->buildCacheKey($location);

        return $this->cache->get($cacheKey, function (CacheItem $item) use ($location) {
            $item->expiresAfter($this->ttl);

            return $this->provider->provide($location);
        });
    }

    private function buildCacheKey(TaxLocation $location): string
    {
        return implode('-', [$location->country->countryCode, $location->state?->stateName]);
    }
}
