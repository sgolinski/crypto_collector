<?php

namespace App\Domain\Event;

use App\Common\Event\DomainEvent;
use App\Common\ValueObjects\Address;
use App\Common\ValueObjects\Chain;
use App\Common\ValueObjects\CryptocurrencyId;
use App\Common\ValueObjects\Name;
use App\Common\ValueObjects\Price;
use DateTimeImmutable;

class PotentialPumpAndDumpRecognized implements DomainEvent
{
    private CryptocurrencyId $id;

    private Address $address;

    private Name $name;

    private Price $price;

    private Chain $chain;

    private DateTimeImmutable $occurredOn;

    public function __construct(
        CryptocurrencyId $cryptocurrencyId,
        Address          $address,
        Name             $name,
        Price            $price,
        Chain            $chain,
    ) {
        $this->id = $cryptocurrencyId;
        $this->address = $address;
        $this->name = $name;
        $this->price = $price;
        $this->chain = $chain;
        $this->occurredOn = new DateTimeImmutable();
    }

    public function id(): CryptocurrencyId
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

    public function price(): Price
    {
        return $this->price;
    }

    public function chain(): Chain
    {
        return $this->chain;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }

}