<?php

namespace App\Domain\QueryHandler;

use App\Domain\Query\CryptocurrencyQueryByAddress;
use App\Infrastructure\Repository\CryptocurrencyRepository;

class CryptocurrencyQueryHandlerByAddress
{

    private CryptocurrencyRepository $cryptocurrencyRepository;

    public function __construct(CryptocurrencyRepository $cryptocurrencyRepository)
    {
        $this->cryptocurrencyRepository = $cryptocurrencyRepository;
    }

    public function __invoke(CryptocurrencyQueryByAddress $cryptocurrencyQueryByName): bool
    {
        return $this->cryptocurrencyRepository->byAddress($cryptocurrencyQueryByName->address());
    }
}