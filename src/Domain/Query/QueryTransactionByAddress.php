<?php

namespace App\Domain\Query;

use App\Common\ValueObjects\Address;

class QueryTransactionByAddress
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
