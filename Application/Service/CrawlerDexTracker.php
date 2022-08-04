<?php

namespace App\Application\Service;

use App\Infrastructure\Repository\CryptocurrencyRepository;
use App\Infrastructure\Repository\PDOCryptocurrencyRepository;
use Symfony\Component\Panther\Client as PantherClient;

abstract class CrawlerDexTracker
{
    protected PantherClient $client;

    protected CryptocurrencyRepository $cryptocurrencyRepository;

    public function __construct(PDOCryptocurrencyRepository $cryptocurrencyRepository)
    {
        $this->cryptocurrencyRepository = $cryptocurrencyRepository;
    }

    protected function getCrawlerForWebsite(
        string $url
    ): void {
        $this->client = PantherClient::createChromeClient();
        $this->client->start();
        $this->client->get($url);
        usleep(30000);
        $this->client->refreshCrawler();
        usleep(30000);
    }
}
