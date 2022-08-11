<?php

namespace App\Domain\QueryHandler;

use App\Domain\Query\QueryNotCompleteTransactions;
use App\Infrastructure\Repository\CryptocurrencyRepository;

class QueryNotCompletedTransactionsHandler
{
    private CryptocurrencyRepository $cryptocurrencyRepository;

    public function __construct(CryptocurrencyRepository $cryptocurrencyRepository)
    {
        $this->cryptocurrencyRepository = $cryptocurrencyRepository;
    }
    public function __invoke(QueryNotCompleteTransactions $cryptocurrencyQuery): array
    {
        return $this->cryptocurrencyRepository->findAllNotComplete();
    }
}
