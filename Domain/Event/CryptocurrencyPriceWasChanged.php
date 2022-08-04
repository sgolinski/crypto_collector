<?php

namespace App\Domain\Event;

use App\Common\Event\DomainEvent;
use App\Common\ValueObjects\CryptocurrencyId;
use App\Common\ValueObjects\Price;
use DateTimeImmutable;

class CryptocurrencyPriceWasChanged implements DomainEvent
{
    private CryptocurrencyId $id;
    private Price $price;
    private DateTimeImmutable $occurredOn;

    public function __construct(CryptocurrencyId $id, Price $price)
    {
        $this->id = $id;
        $this->price = $price;
        $this->occurredOn = new DateTimeImmutable();
    }

    public function id(): CryptocurrencyId
    {
        return $this->id;
    }

    public function price(): Price
    {
        return $this->price;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
