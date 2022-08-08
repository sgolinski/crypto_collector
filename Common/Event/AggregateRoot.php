<?php

namespace App\Common\Event;

use App\Domain\DomainEventPublisher;
use App\Domain\Event\CryptocurrencyTransactionCached;

class AggregateRoot
{
    /** @var DomainEvent[] */
    private array $recordedEvents = [];

    protected function recordApplyAndPublishThat(DomainEvent $event): void
    {
        $this->recordThat($event);
        $this->applyThat($event);
        $this->publishThat($event);
    }

    protected function recordThat(DomainEvent $event): void
    {
        $this->recordedEvents[] = $event;
    }

    protected function applyThat(DomainEvent $event): void
    {
        $className = (new \ReflectionClass($event))->getShortName();

        $modifier = 'apply' . $className;

        $this->$modifier($event);
    }

    protected function publishThat(DomainEvent $event): void
    {
        DomainEventPublisher::instance()->publish($event);
    }

    protected function recordAndApply(CryptocurrencyTransactionCached $event): void
    {
        if (!$this->checkIfIsAlreadyRecorded($event)) {
            $this->recordThat($event);
            $this->applyThat($event);
        }
    }

    /** @return DomainEvent[] */
    public function recordedEvents(): array
    {
        return $this->recordedEvents;
    }

    public function clearEvents(): void
    {
        $this->recordedEvents = [];
    }

    private function checkIfIsAlreadyRecorded(CryptocurrencyTransactionCached $event): int
    {
        foreach ($this->recordedEvents() as $recordedEvent) {
            if ($recordedEvent instanceof CryptocurrencyTransactionCached) {
                if ($event->address()->asString() === $recordedEvent->address()->asString()) {
                    if ($event->price()->asFloat() !== $recordedEvent->price()->asFloat) {
                        $event->noticeRepetitions();
                    }
                    return true;
                }
            }
        }
        return false;
    }
}
