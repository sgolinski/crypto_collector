<?php

namespace App\Infrastructure\Repository;

use App\Common\ValueObjects\Address;
use App\Common\ValueObjects\Holders;
use App\Common\ValueObjects\Name;
use App\Common\ValueObjects\TransactionId;
use App\CryptocurrencyTransaction;

interface CryptocurrencyRepository
{
    public function byId(TransactionId $id): bool;

    public function byName(Name $name): bool;

    public function add(CryptocurrencyTransaction $transaction): void;

    public function update($id, $price): void;

    public function updateHolders(TransactionId $id, Holders $holders);

    public function findAllNotComplete(): array;

    public function addToBlackList(TransactionId $id): void;

    public function updateAlert(TransactionId $id): void;

    public function findAllCompletedNotSent(): array;

    public function byAddress(Address $address): CryptocurrencyTransaction;
}
