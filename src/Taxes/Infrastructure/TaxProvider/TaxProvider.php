<?php

declare(strict_types=1);

namespace App\Taxes\Infrastructure\TaxProvider;

use App\Taxes\Application\TaxDataProviderInterface;
use App\Taxes\Domain\Exception\DomainException;
use App\Taxes\Domain\Exception\NoMatchingProviderException;
use App\Taxes\Domain\TaxProviderInterface;
use App\Taxes\Domain\ValueObject\TaxLocation;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class TaxProvider implements TaxProviderInterface
{
    /**
     * @param TaxDataProviderInterface[] $providers
     */
    public function __construct(#[TaggedIterator(TaxDataProviderInterface::class)] private iterable $providers)
    {
    }

    /**
     * @throws DomainException
     */
    public function provide(TaxLocation $location): array
    {
        $dataProvider = $this->selectProvider($location);

        return $dataProvider->provide($location);
    }

    private function selectProvider(TaxLocation $location): TaxDataProviderInterface
    {
        foreach ($this->providers as $provider) {
            if ($provider->supports($location)) {
                return $provider;
            }
        }
        throw new NoMatchingProviderException('No matching provider for given location');
    }
}
