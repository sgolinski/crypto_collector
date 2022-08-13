<?php

namespace Domain\QueryHandler;

use ArrayIterator;
use Domain\Query\QueryWebElements;
use Infrastructure\Repository\RemoteCryptocurrencyRepository;

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
