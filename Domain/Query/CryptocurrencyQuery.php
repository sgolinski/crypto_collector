<?php

namespace App\Domain\Query;

use App\Common\ValueObjects\CryptocurrencyId;
use InvalidArgumentException;

class CryptocurrencyQuery
{
    private CryptocurrencyId $cryptocurrencyId;

    public function __construct(CryptocurrencyId $cryptocurrencyId)
    {
        $this->cryptocurrencyId = $cryptocurrencyId;
    }

    public function cryptocurrencyId(): CryptocurrencyId
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
