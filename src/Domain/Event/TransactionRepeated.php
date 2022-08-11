<?php

namespace App\Domain\Event;

use App\Common\Event\DomainEvent;
use App\Domain\ValueObjects\Price;
use DateTimeImmutable;

class TransactionRepeated implements DomainEvent
{

    private Price $price;

    public function __construct(Price $price)
    {
        $this->price = $price;
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