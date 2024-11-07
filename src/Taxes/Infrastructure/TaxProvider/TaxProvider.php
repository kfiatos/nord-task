<?php

declare(strict_types=1);

namespace Taxes\Infrastructure\TaxProvider;

use App\Taxes\Domain\ValueObject\TaxLocation;
use Taxes\Application\TaxDataProviderInterface;
use Taxes\Domain\TaxProviderInterface;

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
            if ($provider->supports($taxLocation)) {
                return $provider;
            }
        }
        throw new \Exception('No matching provider for given location');
    }
}
