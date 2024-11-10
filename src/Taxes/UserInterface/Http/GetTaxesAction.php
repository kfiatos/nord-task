<?php

declare(strict_types=1);

namespace App\Taxes\UserInterface\Http;

use App\Shared\Infrastructure\QueryBus\QueryBusInterface;
use App\Taxes\Application\DTO\TaxLocationDto;
use App\Taxes\Application\Query\GetTaxesForCountryQuery;
use App\Taxes\Domain\ValueObject\Country;
use App\Taxes\Domain\ValueObject\CountryState;
use App\Taxes\Domain\ValueObject\TaxLocation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsController]
#[Route('/taxes', name: 'get_taxes', methods: 'GET')]
final readonly class GetTaxesAction
{
    public function __construct(private QueryBusInterface $queryBus, private SerializerInterface $serializer, private ValidatorInterface $validator)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        /** @var string|null $state */
        $state = $request->get('state');
        /** @var string $country */
        $country = $request->get('country', '');

        $dto = new TaxLocationDto($country, $state);
        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            return new JsonResponse($this->prepareErrorMessage($errors), Response::HTTP_BAD_REQUEST);
        }

        try {
            $results = $this->queryBus->query(
                new GetTaxesForCountryQuery(new TaxLocation(new Country($dto->country), $dto->state ? new CountryState($dto->state) : null))
            );

            return new JsonResponse($this->serializer->serialize($results, JsonEncoder::FORMAT, [AbstractObjectNormalizer::SKIP_NULL_VALUES => true]), json: true);
        } catch (HandlerFailedException $exception) {
            return new JsonResponse($exception->getPrevious()?->getMessage() ?? 'Application error, please try again', Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @return array<string>
     */
    private function prepareErrorMessage(ConstraintViolationListInterface $constraintViolationList): array
    {
        /** @var ConstraintViolationList $constraintViolationList */
        return array_map(function ($singleViolation) {
            /* @var $singleViolation ConstraintViolation */
            return $singleViolation->getPropertyPath().': '.$singleViolation->getMessage();
        }, (array) $constraintViolationList->getIterator());
    }
}
