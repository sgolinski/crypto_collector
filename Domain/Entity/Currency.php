<?php

namespace App\Domain\Entity;

use App\Common\ValueObjects\Chain;
use App\Common\ValueObjects\Price;

class Currency
{
    public Chain $chain;

    public Price $price;

    public const ALLOWED_PRICE_PER_TOKEN =
        [
            'wbnb' => 8.00,
            'cake' => 760.00,
            'bnb' => 8.00,
            'usdc' => 2522.96,
            'busd' => 2522.96,
            'usdt' => 2522.96,
            'fusdt' => 2522.96,
            'usdp' => 2522.96
        ];

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
