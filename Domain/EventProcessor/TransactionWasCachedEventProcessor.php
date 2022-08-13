<?php

declare(strict_types=1);

namespace Domain\EventProcessor;

use Domain\Event\PotentialDumpAndPumpRecognized;
use Domain\Event\TransactionWasRegistered;
use Domain\EventHandler\TransactionWasCachedEventHandler;
use Infrastructure\Repository\EventStoreCryptocurrencyRepository;

class TransactionWasCachedEventProcessor
{
    private TransactionWasCachedEventHandler $eventHandler;
    private EventStoreCryptocurrencyRepository $eventStoreCryptocurrencyRepository;

    public function __construct(TransactionWasCachedEventHandler $handler)
    {
        $this->eventHandler = $handler;
    }

    public function process(array $transactions): void
    {
        foreach ($transactions as $id => $transaction) {
            foreach ($transaction->recordedEvents() as $event) {
                assert($event instanceof TransactionWasRegistered || PotentialDumpAndPumpRecognized::class);
                $this->eventStoreCryptocurrencyRepository->save($transaction);
                $this->eventHandler->handle($event);
            }
        }
    }

}
