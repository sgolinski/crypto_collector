<?php

namespace App\Service;

use App\Command\CreatePumpAndDump;
use App\CryptocurrencyRepository;
use App\Event\EventDispatcher;
use App\Infrastructure\Read\TransactionRepository;

use App\SlackService\SlackService;
use App\ValueObjects\PumpAndDumpId;


class TransactionDownloadService
{
    private CryptocurrencyRepository $cryptocurrencyRepository;
    private TransactionRepository $pumpAndDumpRepository;
    private SlackService $service;

    public function __construct(
        CryptocurrencyRepository $cryptocurrencyRepository,
        TransactionRepository    $transactionRepository,
        EventDispatcher          $eventDispatcher
    )
    {
        $this->cryptocurrencyRepository = $cryptocurrencyRepository;
        $this->pumpAndDumpRepository = $transactionRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function create(CreatePumpAndDump $command): PumpAndDumpId
    {

        /*
         *
         *   Id    $id,
        Name  $name,
        Price $price,
        Chain $chain
         */
        $this->cryptocurrencyRepository->byId($command->id());

        $pumpAndDump = new PumpAndDump(
            $command->id(),
            $command->name(),
            $command->price(),
            $command->chain()
        );

        $this->pumpAndDumpRepository->save($pumpAndDump);
        return $pumpAndDump->id();
    }

}