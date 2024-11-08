<?php

declare(strict_types=1);

namespace App\Taxes\Application\DTO;

use Symfony\Component\Validator\Constraints as Assert;

readonly class TaxLocationDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 2, max: 2)]
        public string $country,
        #[Assert\NotBlank(allowNull: true)]
        public ?string $state,
    ) {
    }
}
