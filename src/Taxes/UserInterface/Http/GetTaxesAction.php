<?php

declare(strict_types=1);

namespace Taxes\UserInterface\Http;

use Shared\Infrastructure\QueryBus\QueryBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Taxes\Application\Query\GetTaxesForCountryQuery;
use Taxes\Domain\ValueObject\Country;
use Taxes\Domain\ValueObject\CountryState;
use Taxes\Domain\ValueObject\TaxLocation;

#[AsController]
#[Route('/taxes', name: 'get_taxes', methods: 'GET')]
final readonly class GetTaxesAction
{
    public function __construct(private QueryBusInterface $queryBus)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $country = $request->get('country');
        $state = $request->get('state');

        $results = $this->queryBus->query(
            new GetTaxesForCountryQuery(new TaxLocation(new Country($country), new CountryState($state)))
        );

        return new JsonResponse($results);
    }
}
