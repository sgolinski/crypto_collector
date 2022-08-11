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

    public function settingRepository(PantherCryptocurrencyRepository $repository):void
    {
        $this->remoteCryptocurrencyRepository = $repository;
    }

    public function webElements(): ArrayIterator
    {
        $collectingTransactions = new StartCollectingTransactions();
        $collectingTransactionsHandlerHandler = new StartCollectingTransactionsHandler($this->remoteCryptocurrencyRepository);
        return $collectingTransactionsHandlerHandler->__invoke($collectingTransactions);
    }



}
