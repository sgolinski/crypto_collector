<?php

namespace Domain\QueryHandler;

use App\CryptocurrencyRepository;
use Domain\Event\AggregateRoot;
use Domain\Query\QueryTransactionById;

class QueryTransactionByIdHandler
{
    private CryptocurrencyRepository $cryptocurrencyRepository;

    public function __construct(CryptocurrencyRepository $cryptocurrencyRepository)
    {
        $this->cryptocurrencyRepository = $cryptocurrencyRepository;
    }

    public function __invoke(QueryTransactionById $cryptocurrencyQuery): AggregateRoot
    {
        return $this->cryptocurrencyRepository->byId($cryptocurrencyQuery->cryptocurrencyId());
    }
}
