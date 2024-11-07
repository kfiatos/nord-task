<?php

declare(strict_types=1);

namespace Taxes\Application;

use Taxes\Application\DTO\TaxLocationDto;

readonly class TaxDataProviderStrategy implements TaxProviderInterface
{
    /**
     * @param TaxDataProviderInterface[] $providers
     */
    public function __construct(private array $providers)
    {
    }

    /**
     * @throws \Exception
     */
    public function selectProviderForLocation(TaxLocationDto $taxLocation): TaxDataProviderInterface
    {
        foreach ($this->providers as $provider) {
            if ($provider->supports($taxLocation)) {
                return $provider;
            }
        }
        throw new \Exception('No matching provider for given location');
    }
}
