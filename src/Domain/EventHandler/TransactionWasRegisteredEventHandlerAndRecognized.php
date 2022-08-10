<?php

namespace App\Domain\EventHandler;

use App\Common\Event\DomainEvent;
use App\Common\ValueObjects\Holders;
use App\Domain\Event\TransactionWasRegistered;
use App\Infrastructure\Repository\CryptocurrencyRepository;
use App\Infrastructure\Repository\RemoteCryptocurrencyRepository;
use InvalidArgumentException;

class TransactionWasRegisteredEventHandlerAndRecognized implements EventHandler
{
    private RemoteCryptocurrencyRepository $remoteRepository;
    private CryptocurrencyRepository $repository;

    public function __construct(RemoteCryptocurrencyRepository $remoteRepository, CryptocurrencyRepository $repository)
    {
        $this->remoteRepository = $remoteRepository;
        $this->repository = $repository;
    }

    public function handle(DomainEvent $event): void
    {
        $url = $event->url();
        $holders = $this->remoteRepository->findOneElementOn($url);
        $this->ensureNumberOfHoldersIsBiggerThen($holders);
        $transaction = $this->repository->byAddress($event->address());
        $transaction->assignHolders($holders);
    }

    protected function ensureNumberOfHoldersIsBiggerThen(
        int $holders
    ): void {
        if ($holders < Holders::MIN_AMOUNT_HOLDERS) {
            throw new InvalidArgumentException('Expected number of holders it to low');
        }
    }

    public function supports(DomainEvent $event): bool
    {
        return $event instanceof TransactionWasRegistered;
    }
}
