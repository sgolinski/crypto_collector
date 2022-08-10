<?php

namespace App;

use App\Common\Event\EventSourcedAggregateRoot;
use App\Common\Event\Sourcing\EventStream;
use App\Common\ValueObjects\Chain;
use App\Common\ValueObjects\Holders;
use App\Common\ValueObjects\Name;
use App\Common\ValueObjects\Price;
use App\Common\ValueObjects\Id;
use App\Common\ValueObjects\Url;
use App\Domain\Command\RegisterTransaction;
use App\Domain\CommandHandler\RegisterTransactionHandler;
use App\Domain\Event\HoldersWereAssigned;
use App\Domain\Event\PotentialDumpAndPumpRecognized;
use App\Domain\Event\TransactionWasCached;
use App\Domain\Event\TransactionWasRegistered;
use App\Infrastructure\Repository\PDOCryptocurrencyRepository;

class CryptocurrencyTransaction extends EventSourcedAggregateRoot
{
    private Id $id;
    private Name $name;
    private Price $price;
    private Chain $chain;
    private ?Holders $holders;
    private int $repetitions;
    private ?Url $url;

    private function __construct(Id $transactionId)
    {
        $this->id = $transactionId;
    }

    public static function writeNewFrom(
        Id $id,
        Name    $name,
        Price   $price,
        Chain   $chain
    ): self
    {
        $cryptocurrencyTransaction = new static($id);
        $cryptocurrencyTransaction->recordAndApply(
            new TransactionWasCached(
                $id,
                $name,
                $chain,
                $price,
            )
        );

        return $cryptocurrencyTransaction;
    }

    public static function fromParams(
        Id       $transactionId,
        ?Name    $name,
        ?Price   $price,
        ?Chain   $chain
    ): self
    {
        $transaction = new static($transactionId);
        $transaction->name = $name;
        $transaction->price = $price;
        $transaction->chain = $chain;
        return $transaction;
    }

    public function registerTransaction(): void
    {
        $transactionWasRegistered = new TransactionWasRegistered($this->id());

        $command = new RegisterTransaction($this);
        $repository = new PDOCryptocurrencyRepository();
        $commandHandler = new RegisterTransactionHandler($repository);
        $commandHandler->handle($command);
        $this->recordApplyAndPublishThat($transactionWasRegistered);
    }

    public function assignHolders(int $amountOfHolders): void
    {
        $holders = Holders::fromInt($amountOfHolders);
        $this->holders = $holders;
        $holdersWereAssigned = new HoldersWereAssigned($this->id, $holders);
        $this->recordApplyAndPublishThat($holdersWereAssigned);
    }

    public function registerPumpAndDumpRecognized(int $repetitions)
    {
        $potentialDumpAndPumpTransaction = new PotentialDumpAndPumpRecognized($repetitions);
        $this->recordApplyAndPublishThat($potentialDumpAndPumpTransaction);
    }

    public static function reconstitute(
        EventStream $events
    ): EventSourcedAggregateRoot
    {
        $cryptocurrencyTransaction = new static(new CryptocurrencyTransaction($events->getAggregateId()));
        $cryptocurrencyTransaction->replay($events);

        return $cryptocurrencyTransaction;
    }

    public function applyTransactionWasCached(TransactionWasCached $event): void
    {
        $this->id = $event->id();
        $this->name = $event->name();
        $this->chain = $event->chain();
        $this->price = $event->price();
        $this->repetitions = $event->repetitions();
    }

    public function applyHoldersWereAssigned(HoldersWereAssigned $event): void
    {
        $this->holders = $event->holders();
    }

    public function applyTransactionWasRegistered(TransactionWasRegistered $event): void
    {
        $this->url = $event->url();
    }

    public function applyPotentialDumpAndPumpRecognized(PotentialDumpAndPumpRecognized $event): void
    {
        $this->repetitions = $event->repetitions();
    }

    public function id(): Id
    {
        return $this->id;
    }


    public function name(): Name
    {
        return $this->name;
    }

    public function price(): Price
    {
        return $this->price;
    }

    public function chain(): Chain
    {
        return $this->chain;
    }

    public function repetitions(): ?int
    {
        return $this->repetitions;
    }

    public function url(): ?Url
    {
        return $this->url;
    }

    public function holders(): ?holders
    {
        return $this->holders;
    }
}
