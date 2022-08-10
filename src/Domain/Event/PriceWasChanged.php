<?php

namespace App\Domain\Event;

use App\Common\Event\DomainEvent;

use App\Common\ValueObjects\Price;
use App\Common\ValueObjects\TransactionId;
use DateTimeImmutable;

class PriceWasChanged implements DomainEvent
{
    private TransactionId $id;
    private Price $price;

    public function __construct(TransactionId $id, Price $price)
    {
        $this->id = $id;
        $this->price = $price;
    }

    public function id(): TransactionId
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
