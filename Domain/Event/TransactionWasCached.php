<?php

namespace Domain\Event;


use DateTimeImmutable;
use Domain\ValueObjects\Chain;
use Domain\ValueObjects\Id;
use Domain\ValueObjects\Name;
use Domain\ValueObjects\Price;

class TransactionWasCached implements DomainEvent
{
    private Id $id;
    private Name $name;
    private Chain $chain;
    private Price $price;


    public function __construct(
        Id    $id,
        Name  $name,
        Chain $chain,
        Price $price,
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->chain = $chain;
        $this->price = $price;
    }

    public function id(): Id
    {
        return $this->id;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function chain(): Chain
    {
        return $this->chain;
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
