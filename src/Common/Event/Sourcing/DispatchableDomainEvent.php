<?php

namespace App\Common\Event\Sourcing;

use App\Common\Event\DomainEvent;

class DispatchableDomainEvent
{
    private DomainEvent $domainEvent;

    private int $eventId;

    public function __construct($anEventId, DomainEvent $aDomainEvent)
    {
        $this->domainEvent = $aDomainEvent;
        $this->eventId = $anEventId;
    }

    public function domainEvent(): DomainEvent
    {
        return $this->domainEvent;
    }

    public function eventId(): int
    {
        return $this->eventId;
    }
}
