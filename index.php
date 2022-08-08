<?php


use App\Common\Event\EventStore;
use App\Common\ValueObjects\Url;
use App\Domain\Command\RegisterTransaction;
use App\Domain\CommandHandler\RegisterTransactionHandler;
use App\Domain\Entity\Urls;
use App\Domain\Query\DownloadWebElements;
use App\Domain\Query\FindCryptocurrencyTransactionInWebElement;
use App\Domain\QueryHandler\DownloadWebElementsHandler;
use App\Domain\QueryHandler\FindCryptocurrencyTransactionInWebElementHandler;
use App\Infrastructure\Repository\PantherCryptocurrencyRepository;
use App\Infrastructure\Repository\PDOCryptocurrencyRepository;
use App\Infrastructure\Repository\RedisCryptocurrencyRepository;
use App\Infrastructure\Repository\WebDriverRepository;
use Zumba\JsonSerializer\JsonSerializer;

require './vendor/autoload.php';

$repository = new PDOCryptocurrencyRepository();
$remoteRepository = new PantherCryptocurrencyRepository();
$webdriverRepository = new WebDriverRepository();

$handler = new DownloadWebElementsHandler($remoteRepository);
$redis = new RedisCryptocurrencyRepository();
$jsonSerializer = new JsonSerializer();
$queryHandler = new FindCryptocurrencyTransactionInWebElementHandler($webdriverRepository);
$registerHandler = new RegisterTransactionHandler($repository);
$eventStore = new EventStore($redis->getDb(), $jsonSerializer);

for ($i = 0; $i < 5; $i++) {

    $url = Url::fromString(Urls::URL_CON . $i);
    $query = new DownloadWebElements($url);

    $result = $handler->__invoke($query);


    foreach ($result as $webElement) {

        try {
            $query = new FindCryptocurrencyTransactionInWebElement($webElement);
            $transaction = $queryHandler->__invoke($query);
            if ($transaction) {
                $registerTransactionCommand = new RegisterTransaction($transaction);
                $registerHandler->handle($registerTransactionCommand);
                var_dump($transaction);
            }
        } catch (InvalidArgumentException $exception) {
            echo $exception->getMessage() . PHP_EOL;
        }
    }
}


