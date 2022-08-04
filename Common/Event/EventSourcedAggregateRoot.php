<?php

namespace App\Common\Event;

use App\Common\Event\Sourcing\EventStream;

abstract class EventSourcedAggregateRoot extends AggregateRoot
{
    abstract public static function reconstitute(EventStream $events): EventSourcedAggregateRoot;

    public function replay(EventStream $history): void
    {
        /** @var DomainEvent */
        foreach ($history as $event) {
            $this->applyThat($event);
        }
    }
}
