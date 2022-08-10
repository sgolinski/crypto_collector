<?php

namespace App\Domain\Command;

use App\CryptocurrencyTransaction;

class RegisterTransaction
{
    private CryptocurrencyTransaction $transaction;

    public function __construct(CryptocurrencyTransaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function transaction(): CryptocurrencyTransaction
    {
        return $this->transaction;
    }
}
