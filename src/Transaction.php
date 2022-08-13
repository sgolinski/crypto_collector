<?php

namespace App;

use App\Event\PriceChanged;
use App\ValueObjects\Chain;
use App\ValueObjects\Id;
use App\ValueObjects\Name;
use App\ValueObjects\Price;


final class Transaction
{

    private Id $id;
    private Name $name;
    private Price $price;
    private Chain $chain;

    private array $events = [];

    public function __construct(
        Id    $id,
        Name  $name,
        Price $price,
        Chain $chain,
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->chain = $chain;
    }

    public function changePrice(Price $newPrice): void
    {
        $this->price = $newPrice;
        $this->events[] = new PriceChanged();
    }

    public function recordedEvents(): array
    {
        return $this->events;
    }
}