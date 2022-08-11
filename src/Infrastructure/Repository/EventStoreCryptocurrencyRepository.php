<?php

namespace App\Infrastructure\Repository;

use App\CryptocurrencyTransaction;
use App\Domain\Event\AggregateRoot;
use App\Domain\Event\EventStore;
use App\Domain\Event\Sourcing\EventStream;
use App\Domain\ValueObjects\Id;
use App\Infrastructure\Projection\Projector;

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

    public function save(CryptocurrencyTransaction $transaction): void
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
