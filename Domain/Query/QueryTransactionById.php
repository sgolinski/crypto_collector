<?php

namespace Domain\Query;

use Domain\ValueObjects\Id;
use InvalidArgumentException;

class QueryTransactionById
{
    private Id $cryptocurrencyId;

    public function __construct(Id $cryptocurrencyId)
    {
        $this->cryptocurrencyId = $cryptocurrencyId;
    }
    public function cryptocurrencyId(): Id
    {
        return $this->cryptocurrencyId;
    }

    public function setCryptocurrencyId($cryptocurrencyId): void
    {
        if ($this->cryptocurrencyId === null) {
            throw new InvalidArgumentException('Cannot be empty');
        }
        $this->cryptocurrencyId = $cryptocurrencyId;
    }
}
