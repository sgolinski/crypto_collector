<?php

namespace Infrastructure\Repository;

use App\CryptocurrencyRepository;
use App\Infrastructure\Projection\Projector;
use App\Transaction;
use Domain\Event\AggregateRoot;
use Domain\Event\EventStore;
use Domain\Event\Sourcing\EventStream;
use Domain\ValueObjects\Id;
use SnapshotRepository;

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

    public function byId(Id $id): AggregateRoot
    {
        $snapshot = $this->snapshotRepository->byId($id->id());

        if (null === $snapshot) {
            /** @var Transaction */
            return Transaction::reconstitute(
                $this->eventStore->getEventsFor($id->id())
            );
        }

        /** @var Transaction */
        $cryptocurrency = $snapshot->aggregate();

        $cryptocurrency->replay(
            $this->eventStore->fromVersion($id->id(), $snapshot->version())
        );

        return $cryptocurrency;
    }

    public function save(Transaction $transaction): void
    {
        $id = $transaction->id();

        $events = $transaction->recordedEvents();
        $transaction->clearEvents();

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
                    $transaction,
                    $version
                )
            );
        }

        $this->projector->project($events);
    }

}
