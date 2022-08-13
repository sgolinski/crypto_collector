<?php

namespace Domain\EventHandler;

use App\Common\Event\DomainEvent;
use Domain\Event\TransactionWasCached;
use Infrastructure\Repository\WebElementsService;

class TransactionWasRepeatedHandler
{
    private WebElementsService $cacheRepository;

    public function __construct(WebElementsService $repository)
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