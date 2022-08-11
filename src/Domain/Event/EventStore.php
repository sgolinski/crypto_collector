<?php

namespace App\Domain\Event;

use App\Domain\Event\Sourcing\EventStream;
use Predis\Client;
use Zumba\JsonSerializer\JsonSerializer;

class EventStore
{
    protected Client $redis;
    protected JsonSerializer $serializer;

    public function __construct(Client $redis, JsonSerializer $serializer)
    {
        $this->redis = $redis;
        $this->serializer = $serializer;
    }

    public function append(EventStream $eventstream): void
    {
        /** @var DomainEvent */
        foreach ($eventstream as $event) {
            $data = $this->serializer->serialize($event);

            $date = (new \DateTimeImmutable())->format('YmdHis');

            $payload = $this->serializer->serialize([
                'type' => get_class($event),
                'created_on' => $date,
                'data' => $data
            ]);
            $this->redis->rpush(
                'events:' . $eventstream->getAggregateId(),
                (array)$payload
            );
        }
    }

    public function getEventsFor(string $id): EventStream
    {
        return $this->fromVersion($id, 0);
    }

    public function fromVersion(string $id, int $version): EventStream
    {
        $serializedEvents = (array)$this->redis->lrange(
            'events:' . $id,
            $version,
            -1
        );

        /** @var DomainEvent[] */
        $events = [];

        /** @var string */
        foreach ($serializedEvents as $serializedEvent) {
            $event = (array)$this->serializer->unserialize($serializedEvent);

            $eventData = (string)$event['data'];

            /** @var DomainEvent */
            $events[] = $this->serializer->unserialize($eventData);
        }

        return new EventStream($id, $events);
    }

    public function countEventsFor(string $id): int
    {
        return (int)$this->redis->llen('events:' . $id);
    }

    public function findAllNotComplete()
    {
        $events = $this->redis->dbsize();
        var_dump($events);
    }
}
