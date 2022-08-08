<?php

namespace App\Domain\QueryHandler;

use App\Domain\Query\FindCryptocurrencyTransactionByName;
use App\Infrastructure\Repository\CryptocurrencyRepository;

class FindCryptocurrencyTransactionByNameHandler
{
    private CryptocurrencyRepository $cryptocurrencyRepository;

    public function __construct(CryptocurrencyRepository $cryptocurrencyRepository)
    {
        $this->cryptocurrencyRepository = $cryptocurrencyRepository;
    }

    public function __invoke(FindCryptocurrencyTransactionByName $cryptocurrencyQueryByName): bool
    {
        return $this->cryptocurrencyRepository->byName($cryptocurrencyQueryByName->name());
    }
}
