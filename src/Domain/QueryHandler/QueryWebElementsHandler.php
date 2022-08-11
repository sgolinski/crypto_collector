<?php

namespace App\Domain\QueryHandler;

use App\Domain\Query\QueryWebElements;
use App\Infrastructure\Repository\RemoteCryptocurrencyRepository;
use ArrayIterator;

class QueryWebElementsHandler
{
    private RemoteCryptocurrencyRepository $repository;

    public function __construct(RemoteCryptocurrencyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(QueryWebElements $downloadWebElements): ArrayIterator
    {
        return $this->repository->findElements($downloadWebElements->url());
    }
}
