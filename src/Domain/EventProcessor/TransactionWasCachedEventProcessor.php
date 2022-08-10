<?php

declare(strict_types=1);

namespace App\Domain\EventProcessor;

use App\Domain\EventHandler\TransactionWasCachedEventHandler;

class TransactionWasCachedEventProcessor
{
    private TransactionWasCachedEventHandler $eventHandler;

    public function __construct(TransactionWasCachedEventHandler $handler)
    {
        $this->eventHandler = $handler;
    }

    public function process(array $events): void
    {
        foreach ($events as $key => $event) {
            foreach ($event as $item) {
                $this->eventHandler->handle($item);

            }
        }
    }

}
