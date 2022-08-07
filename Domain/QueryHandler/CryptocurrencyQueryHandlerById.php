<?php

namespace App\Domain\QueryHandler;

use App\Domain\Query\CryptocurrencyQueryById;
use App\Infrastructure\Repository\CryptocurrencyRepository;

class CryptocurrencyQueryHandlerById
{
    private CryptocurrencyRepository $cryptocurrencyRepository;

    public function __construct(CryptocurrencyRepository $cryptocurrencyRepository)
    {
        $this->cryptocurrencyRepository = $cryptocurrencyRepository;
    }

    public function __invoke(CryptocurrencyQueryById $cryptocurrencyQuery): bool
    {
        return $this->cryptocurrencyRepository->byId($cryptocurrencyQuery->cryptocurrencyId());
    }
}
