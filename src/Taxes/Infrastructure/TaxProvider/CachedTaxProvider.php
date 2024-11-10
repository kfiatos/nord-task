<?php

declare(strict_types=1);

namespace App\Taxes\Infrastructure\TaxProvider;

use App\Taxes\Domain\TaxProviderInterface;
use App\Taxes\Domain\ValueObject\TaxDataResultInterface;
use App\Taxes\Domain\ValueObject\TaxLocation;
use Symfony\Component\Cache\CacheItem;
use Symfony\Contracts\Cache\CacheInterface;

final readonly class CachedTaxProvider implements TaxProviderInterface
{
    public function __construct(private TaxProvider $provider, private CacheInterface $cache, private int $ttl)
    {
    }

    /**
     * @return TaxDataResultInterface[]
     */
    public function provide(TaxLocation $location): array
    {
        $cacheKey = $this->buildCacheKey($location);

        /* @phpstan-ignore-next-line */
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
