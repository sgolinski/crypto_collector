<?php

namespace Domain\QueryHandler;

use App\CryptocurrencyRepository;
use Domain\Query\QueryTransactionByName;

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
