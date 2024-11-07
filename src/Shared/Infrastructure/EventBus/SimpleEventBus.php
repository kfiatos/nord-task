<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\EventBus;

final class SimpleEventBus implements EventBus
{
    /**
     * @var array<class-string, callable[]>
     */
    private array $subscribers = [];

    public function publish(DomainEvent $event): void
    {
        $eventClass = get_class($event);
        if (isset($this->subscribers[$eventClass])) {
            foreach ($this->subscribers[$eventClass] as $subscriber) {
                $subscriber($event);
            }
        }
    }

    public function subscribe(string $eventClass, callable $handler): void
    {
        if (!isset($this->subscribers[$eventClass])) {
            $this->subscribers[$eventClass] = [];
        }
        $this->subscribers[$eventClass][] = $handler;
    }
}
