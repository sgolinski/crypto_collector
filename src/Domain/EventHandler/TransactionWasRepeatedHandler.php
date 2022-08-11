<?php

namespace App\Domain\EventHandler;

use App\Common\Event\DomainEvent;
use App\Domain\Event\TransactionWasCached;
use App\Infrastructure\Repository\CacheRepository;

class TransactionWasRepeatedHandler
{
    private CacheRepository $cacheRepository;

    public function __construct(CacheRepository $repository)
    {
        $this->cacheRepository = $repository;
    }

    public function handle(DomainEvent $event): void
    {

    }


    public function supports(DomainEvent $event): bool
    {
        return $event instanceof TransactionWasCached;
    }

}