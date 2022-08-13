<?php

namespace App\Infrastructure\Read;


use App\ValueObjects\Chain;
use App\ValueObjects\Id;
use App\ValueObjects\Name;
use App\ValueObjects\Price;

final class DTOTransaction
{

    private Id $id;
    private Name $name;
    private Price $price;
    private Chain $chain;

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

    /**
     * @return Id
     */
    public function id(): Id
    {
        return $this->id;
    }

    /**
     * @return Name
     */
    public function name(): Name
    {
        return $this->name;
    }

    /**
     * @return Price
     */
    public function price(): Price
    {
        return $this->price;
    }

    /**
     * @return Chain
     */
    public function chain(): Chain
    {
        return $this->chain;
    }

    public function changePrice(Price $newPrice)
    {
        $this->price = $newPrice;
    }


}