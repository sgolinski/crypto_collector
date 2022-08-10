<?php

namespace App\Domain\QueryHandler;

use App\CryptocurrencyTransaction;
use App\Domain\Query\QueryTransactionById;
use App\Infrastructure\Repository\CryptocurrencyRepository;

class QueryTransactionByIdHandler
{
    private CryptocurrencyRepository $cryptocurrencyRepository;

    public function __construct(CryptocurrencyRepository $cryptocurrencyRepository)
    {
        $this->cryptocurrencyRepository = $cryptocurrencyRepository;
    }

    public function __invoke(QueryTransactionById $cryptocurrencyQuery): CryptocurrencyTransaction
    {
        return $this->cryptocurrencyRepository->byId($cryptocurrencyQuery->cryptocurrencyId()->asString());
    }
}
