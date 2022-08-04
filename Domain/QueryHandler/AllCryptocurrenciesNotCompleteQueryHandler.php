<?php

namespace App\Domain\QueryHandler;

use App\Domain\Query\AllCryptocurrenciesNotCompleteQuery;
use App\Infrastructure\Repository\CryptocurrencyRepository;

class AllCryptocurrenciesNotCompleteQueryHandler
{
    private CryptocurrencyRepository $cryptocurrencyRepository;

    public function __construct(CryptocurrencyRepository $cryptocurrencyRepository)
    {
        $this->cryptocurrencyRepository = $cryptocurrencyRepository;
    }

    public function __invoke(AllCryptocurrenciesNotCompleteQuery $cryptocurrencyQuery): array
    {
        return $this->cryptocurrencyRepository->findAllNotComplete();
    }
}
