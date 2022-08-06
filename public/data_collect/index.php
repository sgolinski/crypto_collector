<?php


use App\Domain\CollectCryptocurrency;
use App\Infrastructure\Repository\PDOCryptocurrencyRepository;

require './vendor/autoload.php';

$repository = new PDOCryptocurrencyRepository();

$application = new CollectCryptocurrency($repository);
$application->invoke();
//$name = Name::fromString('fnd');

//$cryptocurrencyQuery = new CryptocurrencyQueryByName($name);
//$cryptocurrencyQueryByNameHandler = new CryptocurrencyQueryHandlerByName($repository);
//$cryptocurrency = $cryptocurrencyQueryByNameHandler->__invoke($cryptocurrencyQuery);
