<?php

namespace App\Infrastructure\Read;

use App\CryptocurrencyRepository;
use App\Transaction;
use App\ValueObjects\Id;

class TransactionRepository implements CryptocurrencyRepository
{

    public function save(Transaction $transaction)
    {
    }

    public function byId(Id $id): Transaction
    {
        // TODO: Implement byId() method.
    }
}