<?php

namespace Infrastructure\Repository;

use ArrayIterator;
use Domain\ValueObjects\Url;

interface RemoteCryptocurrencyRepository
{
    public function findElements(Url $url): ?ArrayIterator;

    public function findOneElementOn(Url $url): int;
}
