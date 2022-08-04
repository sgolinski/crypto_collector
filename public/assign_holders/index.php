<?php

use App\Domain\AssignHolders;
use App\Infrastructure\Repository\PDOCryptocurrencyRepository;

require '../../vendor/autoload.php';

$repository = new PDOCryptocurrencyRepository();

$application = new AssignHolders($repository);
$application->invoke();
