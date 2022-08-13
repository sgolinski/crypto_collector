<?php


use App\Transaction;
use Domain\Event\AggregateRoot;
use Elastic\Transport\Serializer\JsonSerializer;
use Predis\Client;

class SnapshotRepository
{
    private Client $redis;
    private JsonSerializer $serializer;

    public function __construct(Client $redis, JsonSerializer $serializer)
    {
        $this->redis = $redis;
        $this->serializer = $serializer;
    }

    public function byId(string $id): ?\Infrastructure\Repository\Snapshot
    {
        $key = 'snapshots:' . $id;

        /** @var ?string */
        $data = $this->redis->get($key);

        if (null === $data) {
            return null;
        }

        $metadata = $this->serializer->unserialize($data, Transaction::class);

        $snapshot = (array)$metadata['snapshot'];

        /** @var AggregateRoot */
        $aggregate = $this->serializer->unserialize(
            (string)$snapshot['data']
        );

        return new \Infrastructure\Repository\Snapshot(
            $aggregate,
            (int)$metadata['version']
        );
    }

    public function save(string $id, \Infrastructure\Repository\Snapshot $snapshot): void
    {
        $key = 'snapshots:' . $id;
        $aggregate = $snapshot->aggregate();

        $snapshot = [
            'version' => $snapshot->version(),
            'snapshot' => [
                'type' => get_class($aggregate),
                'data' => $this->serializer->serialize(
                    $aggregate
                )
            ]
        ];

        $this->redis->set(
            $key,
            $this->serializer->serialize($snapshot)
        );
    }

    public function has(string $id, int $version): bool
    {
        $snapshot = $this->byId($id);

        if (null === $snapshot) {
            return false;
        }

        return $snapshot->version() === $version;
    }
}
