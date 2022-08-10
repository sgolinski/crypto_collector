<?php

namespace App\Domain\QueryHandler;

use App\Domain\Query\QueryTransactionByName;
use App\Infrastructure\Repository\CryptocurrencyRepository;

class QueryTransactionByNameHandler
{
    private CryptocurrencyRepository $cryptocurrencyRepository;

    public function __construct(CryptocurrencyRepository $cryptocurrencyRepository)
    {
        $this->cryptocurrencyRepository = $cryptocurrencyRepository;
    }

    public function __invoke(QueryTransactionByName $cryptocurrencyQueryByName): bool
    {
        return $this->cryptocurrencyRepository->byName($cryptocurrencyQueryByName->name());
    }
}
