<?php

namespace App\Domain\QueryHandler;

use App\Common\ValueObjects\Url;
use App\Domain\Query\QueryHolders;
use App\Infrastructure\Repository\RemoteCryptocurrencyRepository;

class QueryHoldersHandler
{
    private RemoteCryptocurrencyRepository $repository;

    public function __construct(RemoteCryptocurrencyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(QueryHolders $findHolders): string
    {
        return $this->repository->findOneElementOn($findHolders->url());
    }
}
