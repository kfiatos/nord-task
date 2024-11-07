<?php

declare(strict_types=1);

namespace App\Taxes\Infrastructure\TaxProvider;

use App\Taxes\Application\TaxDataProviderInterface;
use App\Taxes\Domain\TaxProviderInterface;
use App\Taxes\Domain\ValueObject\TaxLocation;

class TaxProvider implements TaxProviderInterface
{
    /**
     * @param TaxDataProviderInterface[] $providers
     */
    public function __construct(private array $providers)
    {
    }

    public function provide(TaxLocation $location)
    {
        foreach ($this->providers as $provider) {
            if ($provider->supports($location)) {
                return $provider;
            }
        }
        throw new \Exception('No matching provider for given location');
    }
}
