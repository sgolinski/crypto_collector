<?php

namespace Domain\Action;

use ArrayIterator;
use Infrastructure\Repository\RemoteCryptocurrencyRepository;

class StartCollectingTransactionsHandler
{
    private RemoteCryptocurrencyRepository $repository;

    public function __construct(RemoteCryptocurrencyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(StartCollectingTransactions $startCollectingTransactions): ArrayIterator
    {
        return $this->repository->findElements($startCollectingTransactions->url());
    }
}
