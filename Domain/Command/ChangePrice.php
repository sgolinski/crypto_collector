<?php

namespace App\Domain\Command;

use App\Common\ValueObjects\CryptocurrencyId;
use App\Common\ValueObjects\Price;

class ChangePrice
{
    public CryptocurrencyId $id;
    public Price $price;

    /**
     * @param CryptocurrencyId $id
     * @param Price $price
     */
    public function __construct(CryptocurrencyId $id, Price $price)
    {
        $this->id = $id;
        $this->price = $price;
    }
}
