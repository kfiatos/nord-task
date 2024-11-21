<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Symfony;

use App\Taxes\Infrastructure\TaxProvider\Exception\ExternalProviderException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

#[AsEventListener(event: ExceptionEvent::class)]
class HandlerFailedExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if ($exception instanceof HandlerFailedException && $exception->getPrevious() instanceof ExternalProviderException) {
            $errorMessage = $exception->getPrevious()->getMessage();
            $response = new JsonResponse(['message' => $errorMessage], Response::HTTP_BAD_REQUEST);
            $event->setResponse($response);

            return;
        }

        if ($exception instanceof HandlerFailedException) {
            $errorMessage = 'Application error, please try again';
            $response = new JsonResponse(['message' => $errorMessage], Response::HTTP_BAD_REQUEST);
            $event->setResponse($response);

            return;
        }
    }
}
