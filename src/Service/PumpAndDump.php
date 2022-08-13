<?php

namespace App\Service;

use App\Event\PumpAndDumpCreated;
use App\ValueObjects\Chain;
use App\ValueObjects\Id;
use App\ValueObjects\Name;
use App\ValueObjects\Price;
use App\ValueObjects\PumpAndDumpId;


class PumpAndDump
{
    private PumpAndDumpId $id;
    private Name $name;
    private Price $price;
    private Chain $chain;
    private array $events;

    /**
     * @param Name $name
     * @param Price $price
     * @param Chain $chain
     */
    public function __construct(
        PumpAndDumpId $id,
        Name          $name,
        Price         $price,
        Chain         $chain
    )
    {
        $this->id = $id;
        $this->events[] = new PumpAndDumpCreated();
        $this->name = $name;
        $this->price = $price;
        $this->chain = $chain;
    }

    public function releaseEvents(): array
    {
        return $this->events;
    }

    public function id(): PumpAndDumpId
    {
        return $this->id;
    }

}