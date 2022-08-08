<?php

namespace App\Domain\Event;

use App\Common\Event\DomainEvent;
use App\Common\ValueObjects\Address;
use App\Common\ValueObjects\Chain;
use App\Common\ValueObjects\Name;
use App\Common\ValueObjects\Price;
use App\Common\ValueObjects\TransactionId;
use DateTimeImmutable;

class CryptocurrencyTransactionCached implements DomainEvent
{
    private TransactionId $id;
    private Address $address;
    private Name $name;
    private Chain $chain;
    private Price $price;
    private int $repeatitions = 0;

    public function __construct(
        TransactionId $transactionId,
        Address       $address,
        Name          $name,
        Chain         $chain,
        Price         $price
    )
    {
        $this->id = $transactionId;
        $this->address = $address;
        $this->name = $name;
        $this->chain = $chain;
        $this->price = $price;
    }

    public function id(): TransactionId
    {
        return $this->id;
    }

    public function address(): Address
    {
        return $this->address;
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
        $this->repeatitions++;
    }
}