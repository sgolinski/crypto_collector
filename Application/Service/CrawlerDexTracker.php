<?php

namespace App\Application\Service;

use App\Infrastructure\Repository\CryptocurrencyRepository;
use App\Infrastructure\Repository\PDOCryptocurrencyRepository;
use Symfony\Component\Panther\Client as PantherClient;

abstract class CrawlerDexTracker
{
    protected CryptocurrencyRepository $cryptocurrencyRepository;
    protected PantherClient $client;

    public function __construct(PDOCryptocurrencyRepository $cryptocurrencyRepository)
    {
        $this->cryptocurrencyRepository = $cryptocurrencyRepository;
    }

    public function getClient(): PantherClient
    {
        return $this->client;
    }

    protected function startClient($url): void
    {
        echo "Start crawling " . date("F j, Y,  H:i:s") . PHP_EOL;
        $this->client = PantherClient::createChromeClient();
        $this->client->start();
        $this->client->get($url);
    }
}
