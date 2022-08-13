<?php

namespace Domain\EventHandler;

use App\Transaction;
use Domain\Entity\Currency;
use Domain\Event\DomainEvent;
use Domain\Event\EventStore;
use Domain\Event\TransactionWasCached;
use Domain\ValueObjects\Chain;
use Domain\ValueObjects\Price;
use Infrastructure\Repository\WebElementsService;
use InvalidArgumentException;

class TransactionWasCachedEventHandler implements EventHandler
{
    private WebElementsService $cacheRepository;
    private EventStore $eventStore;

    public function __construct(WebElementsService $repository, EventStore $eventStore)
    {
        $this->cacheRepository = $repository;

    }

    public function handle(DomainEvent $event): void
    {
        try {
            $this->ensureIsRightInstance($event);
            assert($event instanceof TransactionWasCached);
            $chain = $event->chain();
            $price = $event->price();
            $this->ensurePriceIsHighEnough($chain, $price);

            /** @var Transaction $transaction */
            $transaction = $this->cacheRepository->findOneFromCache($event->id()->asString());
            $transaction->registerTransaction();

        } catch (InvalidArgumentException) {
        }
    }

    private function ensurePriceIsHighEnough(
        Chain $chain,
        Price $price
    ): void
    {
        if ($price->asFloat() < Currency::ALLOWED_PRICE_PER_TOKEN[$chain->asString()]) {
            throw new InvalidArgumentException();
        }
    }

    private function ensureIsRightInstance(DomainEvent $event): void
    {
        if ($event instanceof TransactionWasCached) {
            return;
        }
        throw  new InvalidArgumentException('Wrong instance');
    }
}
