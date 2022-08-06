<?php

namespace App\Domain\Query;

use App\Common\ValueObjects\Address;

class CryptocurrencyQueryByAddress
{

    private Address $address;

    public function __construct(Address $address)
    {
        $this->address = $address;
    }

    public function address()
    {
        return $this->address;
    }
}