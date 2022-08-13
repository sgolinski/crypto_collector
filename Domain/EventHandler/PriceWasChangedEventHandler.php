<?php

namespace Domain\EventHandler;

use App\Common\Event\DomainEvent;
use DateTimeImmutable;
use Domain\ValueObjects\Id;
use Domain\ValueObjects\Price;

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
