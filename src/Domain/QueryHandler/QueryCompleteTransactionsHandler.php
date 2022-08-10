<?php

namespace App\Domain\QueryHandler;

use App\Domain\Query\QueryCompleteTransactions;
use App\Infrastructure\Repository\CryptocurrencyRepository;

class QueryCompleteTransactionsHandler
{
    private CryptocurrencyRepository $cryptocurrencyRepository;

    public function __construct(CryptocurrencyRepository $cryptocurrencyRepository)
    {
        $this->cryptocurrencyRepository = $cryptocurrencyRepository;
    }

    public function __invoke(QueryCompleteTransactions $cryptocurrencyQuery): array
    {
        return $this->cryptocurrencyRepository->findAllCompletedNotSent();
    }
}
