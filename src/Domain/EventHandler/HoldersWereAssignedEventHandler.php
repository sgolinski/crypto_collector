<?php

namespace App\Domain\EventHandler;

use App\Common\Event\DomainEvent;
use App\Domain\ValueObjects\Holders;
use App\Domain\ValueObjects\Id;
use DateTimeImmutable;

class HoldersWereAssignedEventHandler implements DomainEvent
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
