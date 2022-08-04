<?php

namespace App\Repository;

use App\Common\Event\EventStore;
use App\Common\ValueObjects\CryptocurrencyId;
use App\Domain\Event\Sourcing\EventStream;
use App\Infrastructure\Projection\Projector;
use App\Model\Cryptocurrency;

class EventStoreCryptocurrencyRepository implements CryptocurrencyRepository
{
    private SnapshotRepository $snapshotRepository;
    private EventStore $eventStore;
    private Projector $projector;

    public function __construct(
        SnapshotRepository $snapshotRepository,
        EventStore $eventStore,
        Projector $projector
    ) {
        $this->snapshotRepository = $snapshotRepository;
        $this->eventStore = $eventStore;
        $this->projector = $projector;
    }

    public function byId(CryptocurrencyId $id): Cryptocurrency
    {
        $snapshot = $this->snapshotRepository->byId($id->id());

        if (null === $snapshot) {
            /** @var Cryptocurrency */
            return Cryptocurrency::reconstitute(
                $this->eventStore->getEventsFor($id->id())
            );
        }

        /** @var Cryptocurrency */
        $cryptocurrency = $snapshot->aggregate();

        $cryptocurrency->replay(
            $this->eventStore->fromVersion($id->id(), $snapshot->version())
        );

        return $cryptocurrency;
    }

    public function save(Cryptocurrency $cryptocurrency): void
    {
        $id = $cryptocurrency->id();

        $events = $cryptocurrency->recordedEvents();
        $cryptocurrency->clearEvents();

        $this->eventStore->append(
            new EventStream($id->id(), $events)
        );

        $countOfEvents = $this->eventStore->countEventsFor(
            $id->id()
        );

        $version = (int) ($countOfEvents / 100);

        if (!$this->snapshotRepository->has($id->id(), $version)) {
            $this->snapshotRepository->save(
                $id->id(),
                new Snapshot(
                    $cryptocurrency,
                    $version
                )
            );
        }

        $this->projector->project($events);
    }

    public function add(Cryptocurrency $cryptocurrency): void
    {
        // TODO: Implement add() method.
    }
}
