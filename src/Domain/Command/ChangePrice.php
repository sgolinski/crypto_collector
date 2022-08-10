<?php

namespace App\Domain\Command;

use App\Common\ValueObjects\CryptocurrencyId;
use App\Common\ValueObjects\Price;
use App\Common\ValueObjects\TransactionId;

class ChangePrice
{
    public TransactionId $id;
    public Price $price;

    public function __construct(TransactionId $id, Price $price)
    {
        $this->id = $id;
        $this->price = $price;
    }
}
