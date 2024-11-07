<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\EventBus;

interface EventBusInterface
{
    public function publish(DomainEvent $event): void;

    public function subscribe(string $eventClass, callable $handler): void;
}
