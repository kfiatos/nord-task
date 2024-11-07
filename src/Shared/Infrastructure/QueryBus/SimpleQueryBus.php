<?php

declare(strict_types=1);

namespace Shared\Infrastructure\QueryBus;

final class SimpleQueryBus implements QueryBusInterface
{
    private array $handlers = [];

    public function registerHandler(string $commandClass, QueryHandlerInterface $handler): void
    {
        $this->handlers[$commandClass] = $handler;
    }

    public function query(QueryInterface $query): mixed
    {
        $queryClass = get_class($query);
        if (!isset($this->handlers[$queryClass])) {
            throw new \InvalidArgumentException("No handler registered for query {$queryClass}");
        }

        return $this->handlers[$queryClass]->handle($query);
    }
}
