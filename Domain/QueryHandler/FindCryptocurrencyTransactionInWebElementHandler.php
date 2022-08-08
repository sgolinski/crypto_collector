<?php

namespace App\Domain\QueryHandler;

use App\Domain\CryptocurrencyTransaction;
use App\Domain\Query\FindCryptocurrencyTransactionInWebElement;
use App\Infrastructure\Repository\WebDriverRepository;

class FindCryptocurrencyTransactionInWebElementHandler
{
    private WebDriverRepository $repository;

    public function __construct(WebDriverRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(FindCryptocurrencyTransactionInWebElement $findCryptocurrencyTransactionInWebElement): ?CryptocurrencyTransaction
    {
        return $this->repository->findTransactionByWebElement($findCryptocurrencyTransactionInWebElement->element());
    }
}