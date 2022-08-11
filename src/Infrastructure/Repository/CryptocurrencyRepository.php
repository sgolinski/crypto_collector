<?php

namespace App\Infrastructure\Repository;


use App\CryptocurrencyTransaction;
use App\Domain\Event\AggregateRoot;
use App\Domain\ValueObjects\Id;

interface CryptocurrencyRepository
{
    public function save(CryptocurrencyTransaction $transaction): void;

    public function byId(Id $id): AggregateRoot;

}
