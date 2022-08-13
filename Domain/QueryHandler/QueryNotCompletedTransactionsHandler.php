<?php

namespace Domain\QueryHandler;

use App\CryptocurrencyRepository;
use Domain\Query\QueryNotCompleteTransactions;

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
