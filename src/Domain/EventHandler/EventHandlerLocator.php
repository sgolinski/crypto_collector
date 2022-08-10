<?php

declare(strict_types=1);

namespace App\Domain\EventHandler;

use App\Common\Event\DomainEvent;
use App\Domain\Event\PotentialDumpAndPumpRecognized;
use App\Domain\Event\TransactionWasCached;
use App\Domain\Event\TransactionWasRegistered;

class EventHandlerLocator
{
    private $eventHandlers;

    private $supportedEventTypes = [
        TransactionWasCached::class,
        TransactionWasRegistered::class,
        PotentialDumpAndPumpRecognized::class,
    ];

    public function __construct(array $eventHandlers)
    {
        $this->eventHandlers = $eventHandlers;
    }

    public function locateHandlerFor(DomainEvent $event): ?EventHandler
    {
        foreach ($this->eventHandlers as $eventHandler) {
            if ($eventHandler->supports($event)) {
                return $eventHandler;
            }
        }
        return null;
    }

    public function getSupportedEventTypes(): array
    {
        return $this->supportedEventTypes;
    }
}
