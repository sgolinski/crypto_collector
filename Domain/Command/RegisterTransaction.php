<?php

namespace App\Domain\Command;

use App\Domain\CryptocurrencyTransaction;


class RegisterTransaction
{
    private CryptocurrencyTransaction $transaction;

    public function __construct(CryptocurrencyTransaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function transaction(): CryptocurrencyTransaction
    {
        echo 'zwrot';
        return $this->transaction;
    }
}
