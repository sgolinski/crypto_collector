<?php

namespace Domain\Command;

use App\Common\ValueObjects\CryptocurrencyId;
use Domain\ValueObjects\Id;
use Domain\ValueObjects\Price;

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
