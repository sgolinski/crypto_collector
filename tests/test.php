<?php
namespace Tests;

use App\Domain\EventHandler\TransactionWasCachedEventHandler;
use App\Domain\EventProcessor\TransactionWasCachedEventProcessor;
use App\Domain\Query\QueryTransactionsFromWebElements;
use App\Domain\QueryHandler\QueryTransactionsFromWebElementsHandler;
use App\Infrastructure\Repository\CacheRepository;
use App\Infrastructure\Repository\PantherCryptocurrencyRepository;
use App\ProcessManager;

$processManager = new ProcessManager();
$pantherREpository = new PantherCryptocurrencyRepository();
$processManager->settingRepository($pantherREpository);
$webElements = $processManager->webElements();

$cacheRepository = new CacheRepository();

$queryHandler = new QueryTransactionsFromWebElementsHandler($cacheRepository);

$query = new QueryTransactionsFromWebElements($webElements);

$cached = $queryHandler->__invoke($query);

$eventHandler = new TransactionWasCachedEventHandler($cacheRepository);

$eventProcessor = new TransactionWasCachedEventProcessor($eventHandler);