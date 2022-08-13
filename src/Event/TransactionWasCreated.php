<?php

namespace App\Event;

use App\ValueObjects\Id;
use App\ValueObjects\Price;

class TransactionWasCreated
{
    private Id $id;
    private Price $newPrice;

    public function __construct(Id $id, Price $price)
    {
        $this->id = $id;
        $this->newPrice = $price;
    }

    /**
     * @return Id
     */
    public function id(): Id
    {
        return $this->id;
    }

    /**
     * @return Price
     */
    public function newPrice(): Price
    {
        return $this->newPrice;
    }
}