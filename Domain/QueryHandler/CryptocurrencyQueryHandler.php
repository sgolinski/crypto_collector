<?php

namespace App\Domain\QueryHandler;

use App\Domain\Model\Cryptocurrency;
use App\Domain\Query\CryptocurrencyQuery;
use App\Infrastructure\Repository\CryptocurrencyRepository;

class CryptocurrencyQueryHandler
{
    private CryptocurrencyRepository $cryptocurrencyRepository;

    public function __construct(CryptocurrencyRepository $cryptocurrencyRepository)
    {
        $this->cryptocurrencyRepository = $cryptocurrencyRepository;
    }

    public function __invoke(CryptocurrencyQuery $cryptocurrencyQuery): Cryptocurrency
    {
        return $this->cryptocurrencyRepository->byId($cryptocurrencyQuery->cryptocurrencyId());
    }
}
