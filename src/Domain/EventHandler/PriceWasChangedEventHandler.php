<?php

namespace App\Domain\Event;

use App\Common\Event\DomainEvent;
use App\Domain\ValueObjects\Id;
use App\Domain\ValueObjects\Price;
use DateTimeImmutable;

class PriceWasChangedEventHandler implements DomainEvent
{
    private Id $id;
    private Price $price;
    private DateTimeImmutable $occurredOn;

    public function __construct(Id $id, Price $price)
    {
        $this->id = $id;
        $this->price = $price;
        $this->occurredOn = new DateTimeImmutable();
    }

    public function id(): Id
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
