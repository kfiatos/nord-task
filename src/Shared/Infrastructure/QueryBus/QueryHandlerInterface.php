<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\QueryBus;

interface QueryHandlerInterface
{
    public function handle(QueryInterface $query): mixed;
}
