<?php

namespace App\Domain\Entity;

use App\Common\ValueObjects\Chain;
use App\Common\ValueObjects\Price;

class Currency
{
    public Chain $chain;

    public Price $price;

    public function __construct(
        Chain $chain,
        Price $price
    ) {
        $this->chain = $chain;
        $this->price = $price;
    }

    public function getChain(): Chain
    {
        return $this->chain;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }
}
