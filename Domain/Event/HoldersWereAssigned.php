<?php

namespace Domain\Event;

use DateTimeImmutable;
use Domain\ValueObjects\Holders;
use Domain\ValueObjects\Id;

class HoldersWereAssigned implements DomainEvent
{
    private Id $id;
    private Holders $holders;
    private DateTimeImmutable $occurredOn;

    public function __construct(Id $id, Holders $holders)
    {
        $this->id = $id;
        $this->holders = $holders;
        $this->occurredOn = new DateTimeImmutable();
    }

    public function id(): Id
    {
        return $this->id;
    }

    public function holders(): Holders
    {
        return $this->holders;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
