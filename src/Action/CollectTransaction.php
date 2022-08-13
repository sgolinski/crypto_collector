<?php

namespace App\Action;

use App\CryptocurrencyRepository;
use App\Event\EventDispatcher;
use App\Infrastructure\Read\DTOTransaction;
use App\Infrastructure\Read\GetPrice;
use App\Service\PantherService;
use App\Service\TransactionDownloadService;
use App\SlackService\SlackService;
use App\ValueObjects\Id;
use App\ValueObjects\Price;
use App\ValueObjects\PumpAndDumpId;
use Domain\Event\AggregateRoot;

class CollectTransaction
{
    private GetPrice $getPrice;
    private CryptocurrencyRepository $repository;
    private EventDispatcher $eventDispatcher;
    private SlackService $slack;
    private PantherService $panhter;
    private TransactionDownloadService $service;

    public function __construct(
        CryptocurrencyRepository   $repository,
        TransactionDownloadService $service,
        SlackService               $slack,
        PantherService             $panther)
    {
        $this->repository = $repository;
        $this->service = $service;
        $this->slack = $slack;
        $this->panhter = $panther;
    }

    public function crawlTransactionAction(DTOTransaction $DTOTransaction): float
    {
        // extract reqwuest ot get params and pass to service which will create transaction


        $transaction = $this->service->create(
            PumpAndDumpId::fromString($DTOTransaction->id()->asString()),
            $DTOTransaction->price(),
            $DTOTransaction->price(),
            $DTOTransaction->name(),);
        //DEPENDENCY CONTAINER

        // Slack moze wyslac iadomoasc

        // sending alet after receiving TransactionWasREgistered
        $this->slack->sendMessage();


    }

    public function changePriceAction(Id $id): void
    {
        $transaction = $this->repository->byId($id);
        $transaction->changePrice(Price::fromFloat(10.22));
        $this->repository->save($transaction);
        $this->eventDispatcher->dispatchAll($transaction->recordedEvents());
    }


    public function listAllTransactions()
    {

    }

}