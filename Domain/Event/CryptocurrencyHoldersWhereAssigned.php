<?php

namespace App\Domain\Event;

use App\Common\Event\DomainEvent;
use App\Common\ValueObjects\CryptocurrencyId;
use App\Common\ValueObjects\Holders;
use DateTimeImmutable;

class CryptocurrencyHoldersWhereAssigned implements DomainEvent
{
    private CryptocurrencyId $id;
    private Holders $holders;
    private DateTimeImmutable $occurredOn;

    public function __construct(CryptocurrencyId $id, Holders $holders)
    {
        $this->id = $id;
        $this->holders = $holders;
        $this->occurredOn = new DateTimeImmutable();
    }

    public function id(): CryptocurrencyId
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
