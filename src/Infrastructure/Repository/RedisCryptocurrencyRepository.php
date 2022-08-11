<?php

namespace App\Infrastructure\Repository;

use App\CryptocurrencyTransaction;
use App\Domain\Event\AggregateRoot;
use App\Domain\ValueObjects\Id;
use Exception;
use Predis\Client;

class RedisCryptocurrencyRepository implements CryptocurrencyRepository
{
    private Client $db;

    public function __construct()
    {
        try {
            $this->db = new Client([
                'host' => '127.0.0.1' // docker container name, app_redis
            ]);
        } catch (Exception $exception) {
            echo 'Not connected';
        }
    }

    /**
     * @return Client
     */
    public function getDb(): Client
    {
        return $this->db;
    }

    public function save(CryptocurrencyTransaction $transaction): void
    {
        $this->db->set($transaction->id()->asString(), serialize($transaction));

    }

    public function byId(Id $id): AggregateRoot
    {
        $snapshot = $this->snapshotRepository->byId($id->id());

        if (null === $snapshot) {
            /** @var CryptocurrencyTransaction */
            return CryptocurrencyTransaction::reconstitute(
                $this->eventStore->getEventsFor($id->id())
            );
        }

        /** @var CryptocurrencyTransaction */
        $cryptocurrency = $snapshot->aggregate();

        $cryptocurrency->replay(
            $this->eventStore->fromVersion($id->id(), $snapshot->version())
        );

        return $cryptocurrency;
    }

    public function addPotentialPumpAndDump(CryptocurrencyTransaction $transaction)
    {
        // TODO: Implement addPotentialPumpAndDump() method.
    }
}
