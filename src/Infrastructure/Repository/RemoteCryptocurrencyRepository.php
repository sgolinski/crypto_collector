<?php

namespace App\Infrastructure\Repository;

use App\Domain\ValueObjects\Url;
use ArrayIterator;

interface RemoteCryptocurrencyRepository
{
    public function findElements(Url $url): ?ArrayIterator;

    public function findOneElementOn(Url $url): int;
}
