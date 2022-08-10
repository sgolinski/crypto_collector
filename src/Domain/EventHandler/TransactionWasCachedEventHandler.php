<?php

namespace App\Domain\EventHandler;

use App\Common\Event\DomainEvent;
use App\Common\ValueObjects\Chain;
use App\Common\ValueObjects\Price;
use App\CryptocurrencyTransaction;
use App\Domain\Entity\Currency;
use App\Domain\Entity\Names;
use App\Domain\Event\TransactionWasCached;
use App\Infrastructure\Repository\CacheRepository;
use InvalidArgumentException;

class TransactionWasCachedEventHandler implements EventHandler
{
    private CacheRepository $cacheRepository;

    public function __construct(CacheRepository $repository)
    {
        $this->cacheRepository = $repository;
    }

    public function handle(DomainEvent $event): void
    {


        $repetitions = $event->repetitions();
        if ($repetitions > 10) {
            /** @var CryptocurrencyTransaction $transaction */
            $transaction = $this->cacheRepository->findOneFromCache($event->id());
            $transaction->registerPumpAndDumpRecognized($event->repetitions());
            return;
        }
        try {

            $chain = $event->chain();
            $this->ensureIsAllowedChain($chain);
            $price = $event->price();
            $this->ensurePriceIsHighEnough($chain, $price);
            /** @var CryptocurrencyTransaction $transaction */
            $transaction = $this->cacheRepository->findOneFromCache($event->id()->asString());
            $transaction->registerTransaction();


        } catch (InvalidArgumentException $exception) {

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

    private function ensureIsAllowedChain(Chain $chain): void
    {
        if (!in_array($chain->asString(), Names::ALLOWED_NAMES_FOR_CHAINS)) {
            throw new InvalidArgumentException('Currency not allowed');
        }
    }

    public function supports(DomainEvent $event): bool
    {
        return $event instanceof TransactionWasCached;
    }
}
