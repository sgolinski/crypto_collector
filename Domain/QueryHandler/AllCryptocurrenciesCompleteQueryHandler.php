<?php

namespace App\Domain\QueryHandler;

use App\Domain\Query\AllCryptocurrenciesCompleteQuery;
use App\Infrastructure\Repository\CryptocurrencyRepository;

class AllCryptocurrenciesCompleteQueryHandler
{
    private CryptocurrencyRepository $cryptocurrencyRepository;

    public function __construct(CryptocurrencyRepository $cryptocurrencyRepository)
    {
        $this->cryptocurrencyRepository = $cryptocurrencyRepository;
    }

    public function __invoke(AllCryptocurrenciesCompleteQuery $cryptocurrencyQuery): array
    {
        return $this->cryptocurrencyRepository->findAllCompletedNotSent();
    }
}
