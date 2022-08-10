<?php

namespace App\Domain\Command;

use App\Common\ValueObjects\CryptocurrencyId;
use App\Common\ValueObjects\Price;
use App\Common\ValueObjects\Id;

class ChangePrice
{
    public Id $id;
    public Price $price;

    public function __construct(Id $id, Price $price)
    {
        $this->id = $id;
        $this->price = $price;
    }
}
