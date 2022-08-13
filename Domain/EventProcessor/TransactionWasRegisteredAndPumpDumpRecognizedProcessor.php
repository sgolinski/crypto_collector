<?php

declare(strict_types=1);

namespace Domain\EventProcessor;

use Domain\EventHandler\TransactionWasRegisteredEventHandler;

class TransactionWasRegisteredAndPumpDumpRecognizedProcessor
{
    private TransactionWasRegisteredEventHandler $eventHandler;

    public function __construct(TransactionWasRegisteredEventHandler $handler)
    {
        $this->eventHandler = $handler;
    }

    public function process(array $transactions): void
    {
        foreach ($transactions as $id => $transaction) {
            var_dump($transaction);

            die();
            foreach ($transaction->recordedEvents() as $event) {
                $this->eventHandler->handle($event);

            }
        }
    }

}
