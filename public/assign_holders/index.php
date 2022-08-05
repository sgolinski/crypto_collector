<?php

use App\Domain\AssignHolders;
use App\Infrastructure\Repository\PDOCryptocurrencyRepository;

require '/mnt/app/vendor/autoload.php';

$repository = new PDOCryptocurrencyRepository();

$application = new AssignHolders($repository);
$application->invoke();
