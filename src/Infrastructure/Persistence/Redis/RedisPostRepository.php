<?php

namespace App\Infrastructure\Persistence\Redis;

use App\CryptocurrencyTransaction;
use App\Domain\Event\AggregateRoot;
use App\Domain\ValueObjects\Id;
use App\Infrastructure\Repository\CryptocurrencyRepository;
use Predis\Client;

class RedisPostRepository implements CryptocurrencyRepository
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function save(CryptocurrencyTransaction $aTransaction): void
    {
        $this->client->set($aTransaction->id()->asString(), serialize($aTransaction));
    }

    public function remove(CryptocurrencyTransaction $aTransaction)
    {
        $this->client->del($aTransaction->id()->asString());
    }

    public function postOfId(Id $anId)
    {
        if ($data = $this->client->get($anId->asString())) {
            return unserialize($data, CryptocurrencyTransaction::class);
        }

        return null;
    }

    public function latestPosts(\DateTimeImmutable $sinceADate)
    {
        $latest = $this->filterPosts(
            function (CryptocurrencyTransaction $transaction) use ($sinceADate) {
                return $transaction->createdAt() > $sinceADate;
            }
        );

        $this->sortByCreatedAt($latest);

        return array_values($latest);
    }

    private function filterPosts(callable $fn)
    {
        return array_filter(array_map(
            function ($data) {
                return unserialize($data);
            },
            $this->client->hgetall('posts')
        ), $fn);
    }

    private function sortByCreatedAt(&$posts)
    {
        usort($posts, function (Post $a, Post $b) {
            if ($a->createdAt() == $b->createdAt()) {
                return 0;
            }
            return ($a->createdAt() < $b->createdAt()) ? -1 : 1;
        });
    }

    public function nextIdentity()
    {
        return new PostId();
    }

    public function save(CryptocurrencyTransaction $transaction): void
    {
        // TODO: Implement add() method.
    }

    public function byId(Id $id): AggregateRoot
    {
        // TODO: Implement byId() method.
    }

    public function addPotentialPumpAndDump(CryptocurrencyTransaction $transaction)
    {
        // TODO: Implement addPotentialPumpAndDump() method.
    }
}
