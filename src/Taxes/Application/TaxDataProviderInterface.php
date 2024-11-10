<?php

declare(strict_types=1);

namespace App\Taxes\Application;

use App\Taxes\Application\DTO\ExternalTaxDataResultItem;
use App\Taxes\Domain\ValueObject\TaxLocation;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag]
interface TaxDataProviderInterface
{
    /**
     * @return ExternalTaxDataResultItem[]
     */
    public function provide(TaxLocation $taxLocation): array;

    public function supports(TaxLocation $taxLocationDto): bool;
}
