<?php

namespace App\Domain\QueryHandler;

use App\Domain\Entity\Transaction;
use App\Domain\Query\FindCryptocurrencyTransactionByAddress;
use App\Infrastructure\Repository\CryptocurrencyRepository;

class FindCryptocurrencyTransactionByAddressHandler
{

    private CryptocurrencyRepository $cryptocurrencyRepository;

    public function __construct(CryptocurrencyRepository $cryptocurrencyRepository)
    {
        $this->cryptocurrencyRepository = $cryptocurrencyRepository;
    }

    public function __invoke(FindCryptocurrencyTransactionByAddress $cryptocurrencyQueryByAddress): Transaction
    {
        return $this->cryptocurrencyRepository->byAddress($cryptocurrencyQueryByAddress->address());
    }
}