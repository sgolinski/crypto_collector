<?php

namespace App\Application\Service;

use Symfony\Component\Panther\Client as PantherClient;

abstract class CrawlerDexTracker
{
    protected PantherClient $client;

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
