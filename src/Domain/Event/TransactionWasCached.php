<?php

namespace App\Domain\Event;


use App\Domain\ValueObjects\Chain;
use App\Domain\ValueObjects\Id;
use App\Domain\ValueObjects\Name;
use App\Domain\ValueObjects\Price;
use DateTimeImmutable;

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
