<?php

namespace App\Common\Event\Sourcing;

use App\Common\Event\DomainEvent;

class EventStream implements \Iterator
{
    private string $aggregateId;
    /** @var DomainEvent[] */
    private array $events;

    /**
     * @param string $aggregateId
     * @param DomainEvent[] $events
     */
    public function __construct(string $aggregateId, array $events)
    {
        $this->aggregateId = $aggregateId;
        $this->events = $events;
    }

    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }

    public function rewind(): void
    {
        reset($this->events);
    }

    public function current(): DomainEvent
    {
        return current($this->events);
    }

    public function key(): string
    {
        return key($this->events);
    }

    public function next(): void
    {
        next($this->events);
    }

    public function valid(): bool
    {
        return key($this->events) !== null;
    }
}
