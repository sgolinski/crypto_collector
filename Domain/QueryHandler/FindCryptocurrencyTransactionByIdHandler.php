<?php

namespace App\Domain\QueryHandler;

use App\Domain\Query\FindCryptocurrencyTransactionById;
use App\Infrastructure\Repository\CryptocurrencyRepository;

class FindCryptocurrencyTransactionByIdHandler
{
    private CryptocurrencyRepository $cryptocurrencyRepository;

    public function __construct(CryptocurrencyRepository $cryptocurrencyRepository)
    {
        $this->cryptocurrencyRepository = $cryptocurrencyRepository;
    }

    public function __invoke(FindCryptocurrencyTransactionById $cryptocurrencyQuery): bool
    {
        return $this->cryptocurrencyRepository->byId($cryptocurrencyQuery->cryptocurrencyId());
    }
}
