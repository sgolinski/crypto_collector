<?php

namespace App\Domain\QueryHandler;

use App\CryptocurrencyTransaction;
use App\Domain\Query\QueryTransactionsFromWebElements;
use App\Infrastructure\Repository\CacheRepository;

class QueryTransactionsFromWebElementsHandler
{
    private CacheRepository $repository;

    public function __construct(CacheRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(QueryTransactionsFromWebElements $findCryptocurrencyTransactionInWebElement): array
    {
        return $this->repository->findAllTransactions($findCryptocurrencyTransactionInWebElement->elements());
    }
}
