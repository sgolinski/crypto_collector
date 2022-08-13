<?php

namespace App;

use App\Infrastructure\Read\DTOTransaction;
use App\ValueObjects\Id;

interface CryptocurrencyRepository
{
    public function save(DTOTransaction $transaction): void;

    public function byId(Id $id): Transaction;

}
