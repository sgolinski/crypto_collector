<?php

declare(strict_types=1);

namespace Domain\EventProcessor;

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
use flyeralarm\payment\ratepay\OrderInvoicedEventHandler;
use Psr\Log\LoggerInterface;

class OrderInvoicedEventProcessor extends BaseEventProcessor
{
    public const EVENT_PROCESSOR_NAME = 'orderInvoicedEventProcessor';

    private $eventStoreReader;

    private $eventProcessorEntityWriter;

    private $orderInvoicedEventHandler;

    public function __construct(
        EventStoreReader           $eventStoreReader,
        EventProcessorEntityLoader $eventProcessorEntityLoader,
        EventProcessorEntityWriter $eventProcessorEntityWriter,
        FailedProcessedEventWriter $failedProcessedEventWriter,
        FailedProcessedEventReader $failedProcessedEventReader,
        OrderInvoicedEventHandler  $orderInvoicedEventHandler,
        LoggerInterface            $logger
    ) {
        parent::__construct(
            $eventProcessorEntityLoader,
            $failedProcessedEventWriter,
            $failedProcessedEventReader,
            $logger
        );

        $this->eventStoreReader = $eventStoreReader;
        $this->eventProcessorEntityWriter = $eventProcessorEntityWriter;
        $this->orderInvoicedEventHandler = $orderInvoicedEventHandler;
    }


    public function process(int $maxProcessingEvents = 200): void
    {
        $events = $this->buildEventCollectionFrom('order_invoiced');

        $processed = 0;
        foreach ($events as $event) {
            $this->orderInvoicedEventHandler->handle($event);
            if ($processed >= $maxProcessingEvents) {
                break;
            }
            $processed++;
        }
    }


    public function processOne(string $eventId, bool $bubbleUpException = false): void
    {
        $event = $this->eventStoreReader->readOne($eventId);
    }

    private function buildEventCollectionFrom(string $string)
    {
    }
}
