<?php

namespace App\Infrastructure\Repository;


use App\Common\ValueObjects\Url;
use ArrayIterator;


interface RemoteCryptocurrencyRepository
{
    public function findElements(Url $url): ?ArrayIterator;

    public function findOneElementOn(Url $url): string;
}
