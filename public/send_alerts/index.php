<?php

use App\Application\Service\NotificationService;
use App\Domain\CollectCryptocurrency;
use App\Domain\SendAlerts;
use App\Infrastructure\Repository\PDOCryptocurrencyRepository;

require '../../vendor/autoload.php';

$repository = new PDOCryptocurrencyRepository();
$service = new NotificationService();
$application = new SendAlerts($repository, $service);
$application->invoke();