<?php

namespace App\Infrastructure\Repository;

use App\Common\ValueObjects\Address;
use App\Common\ValueObjects\CryptocurrencyId;
use App\Common\ValueObjects\Holders;
use App\Common\ValueObjects\Name;
use App\Domain\Entity\Cryptocurrency;


interface CryptocurrencyRepository
{
    public function byId(CryptocurrencyId $id): bool;

    public function byName(Name $name): bool;

    public function add(Cryptocurrency $cryptocurrency): void;

    public function update($id, $price): void;

    public function updateHolders(CryptocurrencyId $id, Holders $holders);

    public function findAllNotComplete(): array;

    public function addToBlackList(CryptocurrencyId $id): void;

    public function updateAlert(CryptocurrencyId $id): void;

    public function findAllCompletedNotSent(): array;

    public function byAddress(Address $address): bool;
}
