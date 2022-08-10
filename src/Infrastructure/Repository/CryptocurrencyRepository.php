<?php

namespace App\Infrastructure\Repository;

use App\Common\ValueObjects\Address;
use App\Common\ValueObjects\Holders;
use App\Common\ValueObjects\Name;
use App\Common\ValueObjects\Id;
use App\CryptocurrencyTransaction;

interface CryptocurrencyRepository
{
    public function add(CryptocurrencyTransaction $transaction): void;

    public function byId(string $id): CryptocurrencyTransaction;
}
