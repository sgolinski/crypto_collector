<?php

declare(strict_types=1);

namespace App\Domain\EventProcessor;

use App\Domain\EventHandler\TransactionWasCachedEventHandler;

class TransactionWasRegisteredAndPumpDumpRecognizedProcessor
{
    private TransactionWasCachedEventHandler $eventHandler;

    public function __construct(TransactionWasCachedEventHandler $handler)
    {
        $this->eventHandler = $handler;
    }

    public function process(array $transactions): void
    {
        foreach ($transactions as $id => $transaction) {
            foreach ($transaction->recordedEvents() as $event) {
                $this->eventHandler->handle($event);

            }
        }
    }

}
