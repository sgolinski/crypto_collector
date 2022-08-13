<?php

namespace Domain\Event;

use DateTimeImmutable;
use Domain\ValueObjects\Id;
use Domain\ValueObjects\Price;

class PriceWasChanged implements DomainEvent
{
    private Id $id;
    private Price $price;

    public function __construct(Id $id, Price $price)
    {
        $this->id = $id;
        $this->price = $price;
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
        return new DateTimeImmutable();
    }
}
