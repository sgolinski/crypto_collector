<?php


use App\Domain\Event\EventStore;
use App\Domain\EventHandler\TransactionWasRegisteredEventHandler;
use App\Domain\EventProcessor\TransactionWasRegisteredAndPumpDumpRecognizedProcessor;
use App\Domain\Query\QueryNotCompleteTransactions;
use App\Domain\QueryHandler\QueryNotCompletedTransactionsHandler;
use App\Infrastructure\Repository\PantherCryptocurrencyRepository;
use App\Infrastructure\Repository\PDOCryptocurrencyRepository;
use Predis\Client;
use Zumba\JsonSerializer\JsonSerializer;

require './vendor/autoload.php';

$redis = new CLIENT('127.0.0.1:6379');
$serializer = new JsonSerializer();
$eventStore = new EventStore($redis, $serializer);


// pobierz wszystkie transakcje z bazy
$query = new QueryNotCompleteTransactions();
$repository = new PDOCryptocurrencyRepository($eventStore);
$queryHandler = new QueryNotCompletedTransactionsHandler($repository);

$completed = $queryHandler->__invoke($query);
// zapisz uzytkownikow

$remoteRepository = new PantherCryptocurrencyRepository();

$transactionWasRegisteredHandler = new TransactionWasRegisteredEventHandler($remoteRepository, $repository);
$processor = new TransactionWasRegisteredAndPumpDumpRecognizedProcessor($transactionWasRegisteredHandler);
// zrob update

// done