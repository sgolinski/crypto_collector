<?php

use App\Application\Service\NotificationService;
use App\Domain\CollectCryptocurrency;
use App\Domain\SendAlerts;
use App\Infrastructure\Repository\PDOCryptocurrencyRepository;

require '/mnt/app/vendor/autoload.php';

$repository = new PDOCryptocurrencyRepository();
$service = new NotificationService();
$application = new SendAlerts($repository, $service);
$application->invoke();
