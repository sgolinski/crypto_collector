<?php

namespace App;

use App\Domain\Command\RegisterPotentialPumpAndDump;
use App\Domain\Command\RegisterTransaction;
use App\Domain\CommandHandler\RegisterPotentialPumpAndDumpHandler;
use App\Domain\CommandHandler\RegisterTransactionHandler;
use App\Domain\Event\EventSourcedAggregateRoot;
use App\Domain\Event\EventStore;
use App\Domain\Event\HoldersWereAssigned;
use App\Domain\Event\PotentialDumpAndPumpRecognized;
use App\Domain\Event\Sourcing\EventStream;
use App\Domain\Event\TransactionRepeated;
use App\Domain\Event\TransactionWasCached;
use App\Domain\Event\TransactionWasRegistered;
use App\Domain\ValueObjects\Chain;
use App\Domain\ValueObjects\Holders;
use App\Domain\ValueObjects\Id;
use App\Domain\ValueObjects\Name;
use App\Domain\ValueObjects\Price;
use App\Domain\ValueObjects\Url;
use App\Infrastructure\Repository\PDOCryptocurrencyRepository;
use Assert\Assertion;
use DateTimeImmutable;
use Predis\Client;
use Zumba\JsonSerializer\JsonSerializer;

class CryptocurrencyTransaction extends EventSourcedAggregateRoot
{
    private Id $id;
    private Name $name;
    private Price $price;
    private Chain $chain;
    private ?Holders $holders;
    private int $repetitions;
    private ?Url $url;
    private bool $isComplete;
    private bool $isBlacklisted;
    private EventStream $eventStream;
    private $createdAt;

    private function __construct(Id $transactionId)
    {
        $this->id = $transactionId;
        $this->repetitions = 1;
        $this->eventStream = new EventStream($transactionId->asString(), $this->recordedEvents());
        $this->createdAt = new DateTimeImmutable();
    }

    public static function writeNewFrom(
        Id    $id,
        Name  $name,
        Price $price,
        Chain $chain
    ): self
    {
        $cryptocurrencyTransaction = new static($id);
        $cryptocurrencyTransaction->recordAndApply(
            new TransactionWasCached(
                $id,
                $name,
                $chain,
                $price
            )
        );

        return $cryptocurrencyTransaction;
    }

    public static function fromParams(
        Id    $transactionId,
        Name  $name,
        Price $price,
        Chain $chain
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
        $redis = new Client('127.0.0.1');
        $json = new JsonSerializer();
        $eventStore = new EventStore($redis, $json);
        $repository = new PDOCryptocurrencyRepository($eventStore);
        $commandHandler = new RegisterTransactionHandler($repository);

        $commandHandler->handle($command);
        $this->recordApplyAndPublishThat($transactionWasRegistered);
    }

    public function transactionRepeated(Price $price): void
    {
        $transactionRepeated = new TransactionRepeated($price);
        $this->applyThat($transactionRepeated);
    }

    public function assignHolders(int $amountOfHolders): void
    {
        $holders = Holders::fromInt($amountOfHolders);
        $this->holders = $holders;
        $holdersWereAssigned = new HoldersWereAssigned($this->id, $holders);
        $this->recordApplyAndPublishThat($holdersWereAssigned);
    }

    public function noticeRepetitions(): void
    {
        $this->repetitions++;

        if ($this->repetitions >= 5) {
            $this->registerPumpAndDumpRecognized();
        }
    }

    private function registerPumpAndDumpRecognized(): void
    {
        $potentialDumpAndPumpTransaction = new PotentialDumpAndPumpRecognized();
        $command = new RegisterPotentialPumpAndDump($this);
        $redis = new Client('127.0.0.1');
        $json = new JsonSerializer();
        $eventStore = new EventStore($redis, $json);

        $repository = new PDOCryptocurrencyRepository($eventStore);
        $commandHandler = new RegisterPotentialPumpAndDumpHandler($repository);
        $commandHandler->handle($command);
        $this->recordThat($potentialDumpAndPumpTransaction);
    }

    public static function reconstitute(
        EventStream $events
    ): EventSourcedAggregateRoot
    {
        $cryptocurrencyTransaction = new static(Id::fromString($events->getAggregateId()));
        $cryptocurrencyTransaction->replay($events);

        return $cryptocurrencyTransaction;
    }

    public function applyTransactionWasCached(TransactionWasCached $event): void
    {
        $this->id = $event->id();
        $this->name = $event->name();
        $this->chain = $event->chain();
        $this->price = $event->price();
    }

    public function applyHoldersWereAssigned(HoldersWereAssigned $event): void
    {
        $this->holders = $event->holders();
    }

    public function applyTransactionWasRegistered(TransactionWasRegistered $event): void
    {
        $this->url = $event->url();
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

    public function createdAt()
    {
        return $this->createdAt;
    }

}
