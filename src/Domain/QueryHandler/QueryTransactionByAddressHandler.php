<?php

namespace App\Domain\QueryHandler;

use App\Domain\Entity\Transaction;
use App\Domain\Query\QueryTransactionByAddress;
use App\Infrastructure\Repository\CryptocurrencyRepository;

class QueryTransactionByAddressHandler
{
    private CryptocurrencyRepository $cryptocurrencyRepository;

    public function __construct(CryptocurrencyRepository $cryptocurrencyRepository)
    {
        $this->cryptocurrencyRepository = $cryptocurrencyRepository;
    }

    public function __invoke(QueryTransactionByAddress $cryptocurrencyQueryByAddress): Transaction
    {
        return $this->cryptocurrencyRepository->byAddress($cryptocurrencyQueryByAddress->address());
    }
}
