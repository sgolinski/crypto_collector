<?php

namespace Domain\Command;

use App\Transaction;

class RegisterPotentialPumpAndDump
{
    private Transaction $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function transaction(): Transaction
    {
        return $this->transaction;
    }
}
