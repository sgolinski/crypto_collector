<?php

namespace App\Domain\Event;

use App\Common\Event\DomainEvent;
use App\Common\ValueObjects\CryptocurrencyId;
use App\Common\ValueObjects\Percentage;
use DateTimeImmutable;

class CryptocurrencyPercentageWasChanged implements DomainEvent
{
    private CryptocurrencyId $id;

    private Percentage $percentage;

    private DateTimeImmutable $occurredOn;

    public function __construct(CryptocurrencyId $id, Percentage $percentage)
    {
        $this->id = $id;
        $this->percentage = $percentage;
        $this->occurredOn = new DateTimeImmutable();
    }

    public function id(): CryptocurrencyId
    {
        return $this->id;
    }

    public function percentage(): Percentage
    {
        return $this->percentage;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
