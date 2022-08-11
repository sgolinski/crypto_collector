<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\ScriptsJs;
use App\Domain\ValueObjects\Url;
use ArrayIterator;
use Exception;
use Symfony\Component\Panther\Client;

class PantherCryptocurrencyRepository implements RemoteCryptocurrencyRepository
{
    private Client $client;

    public function findElements(Url $url): ?ArrayIterator
    {
        $this->refreshClient($url);
        try {
            return $this->client->getCrawler()
                ->filter(ScriptsJs::CONTENT_SELECTOR_TABLE)
                ->filter(ScriptsJs::CONTENT_SELECTOR_TABLE_BODY)
                ->children()
                ->getIterator();
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
        return null;
    }

    public function findOneElementOn(Url $url): int
    {
        $holders = 0;
        try {
            $this->refreshClient($url);
            $holders = $this->client->getCrawler()
                ->filter(ScriptsJs::HOLDERS_SELECTOR)
                ->getText();
            $holders = (int)$holders;
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
        return $holders;
    }

    private function refreshClient(Url $url): void
    {
        $this->client = Client::createChromeClient();
        $this->client->start();
        $this->client->get($url->asString());
        $this->client->refreshCrawler();
    }
}
