<?php

namespace App\Application\Service;

use App\Infrastructure\Repository\CryptocurrencyRepository;
use App\Infrastructure\Repository\PDOCryptocurrencyRepository;

abstract class CrawlerDexTracker
{
    protected CryptocurrencyRepository $cryptocurrencyRepository;

    public function __construct(PDOCryptocurrencyRepository $cryptocurrencyRepository)
    {
        $this->cryptocurrencyRepository = $cryptocurrencyRepository;
    }

}
