<?php

namespace Domain\QueryHandler;

use App\CryptocurrencyRepository;
use Domain\Query\QueryCompleteTransactions;

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
