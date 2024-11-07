<?php

declare(strict_types=1);

namespace Shared\Infrastructure\QueryBus;

interface QueryBusInterface
{
    public function query(QueryInterface $query): mixed;
}
