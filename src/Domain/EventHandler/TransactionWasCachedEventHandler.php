<?php

namespace App\Domain\EventHandler;

use App\CryptocurrencyTransaction;
use App\Domain\Entity\Currency;
use App\Domain\Event\DomainEvent;
use App\Domain\Event\EventStore;
use App\Domain\Event\TransactionWasCached;
use App\Domain\ValueObjects\Chain;
use App\Domain\ValueObjects\Price;
use App\Infrastructure\Repository\CacheRepository;
use InvalidArgumentException;

class TransactionWasCachedEventHandler implements EventHandler
{
    private CacheRepository $cacheRepository;
    private EventStore $eventStore;

    public function __construct(CacheRepository $repository, EventStore $eventStore)
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

            /** @var CryptocurrencyTransaction $transaction */
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
