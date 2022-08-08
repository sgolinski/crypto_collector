<?php

namespace App\Domain\QueryHandler;

use App\Domain\Query\FindAllCompleteCryptocurrencyTransactions;
use App\Infrastructure\Repository\CryptocurrencyRepository;

class FindAllCompleteCryptocurrencyTransactionsHandler
{
    private CryptocurrencyRepository $cryptocurrencyRepository;

    public function __construct(CryptocurrencyRepository $cryptocurrencyRepository)
    {
        $this->cryptocurrencyRepository = $cryptocurrencyRepository;
    }

    public function __invoke(FindAllCompleteCryptocurrencyTransactions $cryptocurrencyQuery): array
    {
        return $this->cryptocurrencyRepository->findAllCompletedNotSent();
    }
}
