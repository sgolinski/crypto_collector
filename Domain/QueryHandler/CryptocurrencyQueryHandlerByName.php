<?php

namespace App\Domain\QueryHandler;

use App\Domain\Model\Cryptocurrency;
use App\Domain\Query\CryptocurrencyQueryByName;
use App\Infrastructure\Repository\CryptocurrencyRepository;

class CryptocurrencyQueryHandlerByName
{
    private CryptocurrencyRepository $cryptocurrencyRepository;

    public function __construct(CryptocurrencyRepository $cryptocurrencyRepository)
    {
        $this->cryptocurrencyRepository = $cryptocurrencyRepository;
    }

    public function __invoke(CryptocurrencyQueryByName $cryptocurrencyQueryByName): ?Cryptocurrency
    {
        return $this->cryptocurrencyRepository->byName($cryptocurrencyQueryByName->name());
    }
}
