<?php

declare(strict_types=1);

namespace Shared\Infrastructure\QueryBus;

interface QueryHandlerInterface
{
    public function handle(QueryInterface $query): mixed;
}
