<?php

namespace App;

use App\Domain\Action\StartCollectingTransactions;
use App\Domain\Action\StartCollectingTransactionsHandler;
use App\Infrastructure\Repository\PantherCryptocurrencyRepository;
use App\Infrastructure\Repository\RemoteCryptocurrencyRepository;
use ArrayIterator;

class ProcessManager
{
    private RemoteCryptocurrencyRepository $remoteCryptocurrencyRepository;
    private StartCollectingTransactions $collectingTransactions;
    private StartCollectingTransactionsHandler $collectingTransactionsHandlerHandler;

    public function setRepository(PantherCryptocurrencyRepository $repository)
    {
        $this->remoteCryptocurrencyRepository = $repository;
    }

    public function webElements(): ArrayIterator
    {
        $this->collectingTransactions = new StartCollectingTransactions();
        $this->collectingTransactionsHandlerHandler = new StartCollectingTransactionsHandler($this->remoteCryptocurrencyRepository);
        return $this->collectingTransactionsHandlerHandler->__invoke($this->collectingTransactions);
    }
}
