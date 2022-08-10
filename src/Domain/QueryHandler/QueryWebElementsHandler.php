<?php

namespace App\Domain\QueryHandler;

use App\Common\ValueObjects\Url;
use App\Domain\Query\QueryWebElements;
use App\Domain\Query\QueryHolders;
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
