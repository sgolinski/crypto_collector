<?php

namespace App\Domain\Event;

use App\Common\Event\DomainEvent;
use App\Common\ValueObjects\Address;
use App\Common\ValueObjects\Chain;
use App\Common\ValueObjects\Name;
use App\Common\ValueObjects\Price;
use App\Common\ValueObjects\Id;
use DateTimeImmutable;

class TransactionWasCached implements DomainEvent
{
    private Id $id;
    private Name $name;
    private Chain $chain;
    private Price $price;
    private int $repetitions = 0;

    public function __construct(
        Id      $id,
        Name    $name,
        Chain   $chain,
        Price   $price
    ) {
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

    public function noticeRepetitions(): void
    {
        $this->repetitions++;
    }

    public function repetitions(): int
    {
        return $this->repetitions;
    }
}
