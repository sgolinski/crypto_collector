<?php

namespace App\Domain\Command;

use App\Common\ValueObjects\CryptocurrencyId;
use App\Common\ValueObjects\Price;

class ChangePriceCommand
{
    public function __construct(public CryptocurrencyId $id, public Price $price)
    {
    }
}
