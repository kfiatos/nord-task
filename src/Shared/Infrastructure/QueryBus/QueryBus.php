<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\QueryBus;

use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class QueryBus implements QueryBusInterface
{
    public function __construct(private MessageBusInterface $bus)
    {
    }

    public function query(QueryInterface $query): mixed
    {
        return $this->bus->dispatch($query)->last(HandledStamp::class)->getResult();
    }
}
