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
            'wbnb' => 7.00,
            'cake' => 760.00,
            'bnb' => 7.00,
            'usdc' => 2470.00,
            'busd' => 2470.00,
            'usdt' => 2470.00,
            'fusdt' => 2470.00,
            'usdp' => 2470.00,
            'bsc-usd' => 2470.00,
            'bscusd' => 2470.00,
        ];

    public function __construct(
        Chain $chain,
        Price $price
    )
    {
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
