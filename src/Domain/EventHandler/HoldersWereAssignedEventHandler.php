<?php

namespace App\Domain\EventHandler;

use App\Common\Event\DomainEvent;
use App\Common\ValueObjects\TransactionId;
use App\Common\ValueObjects\Holders;
use DateTimeImmutable;

class HoldersWereAssignedEventHandler implements DomainEvent
{
    private TransactionId $id;
    private Holders $holders;
    private DateTimeImmutable $occurredOn;

    public function __construct(TransactionId $id, Holders $holders)
    {
        $this->id = $id;
        $this->holders = $holders;
        $this->occurredOn = new DateTimeImmutable();
    }

    public function id(): TransactionId
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
