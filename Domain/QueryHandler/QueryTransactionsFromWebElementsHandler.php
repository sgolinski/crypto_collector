<?php

namespace Domain\QueryHandler;

use Domain\Query\QueryTransactionsFromWebElements;
use Infrastructure\Repository\WebElementsService;

class QueryTransactionsFromWebElementsHandler
{
    private WebElementsService $repository;

    public function __construct(WebElementsService $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(QueryTransactionsFromWebElements $findCryptocurrencyTransactionInWebElement): array
    {
        return $this->repository->findAllTransactions($findCryptocurrencyTransactionInWebElement->elements());
    }
}
