<?php


use App\CryptocurrencyTransaction;
use App\Domain\EventHandler\TransactionWasCachedEventHandler;
use App\Domain\EventProcessor\TransactionWasCachedEventProcessor;
use App\Domain\Query\QueryTransactionsFromWebElements;
use App\Domain\QueryHandler\QueryTransactionsFromWebElementsHandler;
use App\Infrastructure\Repository\CacheRepository;
use App\Infrastructure\Repository\PantherCryptocurrencyRepository;
use App\ProcessManager;

require './vendor/autoload.php';


$process = new ProcessManager();
$pantherRepository = new PantherCryptocurrencyRepository();
$process->settingRepository($pantherRepository);
$webElements = $process->webElements();


$cacheRepository = new CacheRepository();
$queryHandler = new QueryTransactionsFromWebElementsHandler($cacheRepository);
$query = new QueryTransactionsFromWebElements($webElements);
$cached = $queryHandler->__invoke($query);

foreach ($cached as $item) {
    assert($item instanceof CryptocurrencyTransaction);
    echo $item->id()->asString() . " " . $item->name()->asString() . " " . $item->repetitions() . PHP_EOL;
}
$eventHandler = new TransactionWasCachedEventHandler($cacheRepository);
$eventProcessor = new TransactionWasCachedEventProcessor($eventHandler);

$eventProcessor->process($cached);