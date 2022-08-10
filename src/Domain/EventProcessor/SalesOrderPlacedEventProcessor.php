<?php

declare(strict_types=1);

namespace flyeralarm\payment\ratepay;

use flyeralarm\eventstore\DomainEventCollection;
use flyeralarm\eventstore\EventStoreException;
use flyeralarm\eventstore\EventStoreReader;
use flyeralarm\eventstore\EventType;
use flyeralarm\eventstore\EventTypeCollection;
use flyeralarm\eventstore\UUID;
use flyeralarm\payment\BaseEventProcessor;
use flyeralarm\payment\EventProcessorEntityLoader;
use flyeralarm\payment\EventProcessorEntityWriter;
use flyeralarm\payment\EventProcessorIOException;
use flyeralarm\payment\EventProcessorName;
use flyeralarm\payment\EventProcessorNamespace;
use flyeralarm\payment\FailedProcessedEventReader;
use flyeralarm\payment\FailedProcessedEventWriter;
use flyeralarm\payment\PaymentTransactionException;
use Psr\Log\LoggerInterface;
use Throwable;

class SalesOrderPlacedEventProcessor
{
    public const EVENT_PROCESSOR_NAME = 'salesOrderPlacedEventProcessor';

    private $eventStoreReader;

    private $salesOrderPlacedEventHandler;

    public function __construct(
        $eventStoreReader,
        $eventProcessorEntityWriter,
        $salesOrderPlacedEventHandler
    ) {
        $this->eventStoreReader = $eventStoreReader;
        $this->salesOrderPlacedEventHandler = $salesOrderPlacedEventHandler;
    }

    public function process(int $maxProcessingEvents = 200): void
    {
        $events = $this->buildEventCollectionFrom('sales_order_placed_v3');
        $processed = 0;
        foreach ($events as $event) {
            if ($processed >= $maxProcessingEvents) {
                break;
            }
            $this->salesOrderPlacedEventHandler->handle($event);

            $processed++;
        }
    }

    public function processOne(string $eventId, bool $bubbleUpException = false): void
    {
        $event = $this->eventStoreReader->readOne($eventId);
        $this->salesOrderPlacedEventHandler->handle($event);
    }

    private function buildEventCollectionFrom(string $eventType)
    {
        return $this->eventStoreReader->readAll($eventType);
    }
}
