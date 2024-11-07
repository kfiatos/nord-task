<?php

declare(strict_types=1);

namespace Shared\Domain\Event;

interface DomainEvent
{
    public function getEventId(): EventId;

    public function getOccurredOn(): \DateTimeImmutable;
}
