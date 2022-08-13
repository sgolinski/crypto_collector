<?php

namespace Domain\QueryHandler;

use Domain\Query\QueryHolders;
use Infrastructure\Repository\RemoteCryptocurrencyRepository;

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
