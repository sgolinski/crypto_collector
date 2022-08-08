<?php

namespace App\Domain;

use App\Common\Event\EventSourcedAggregateRoot;
use App\Common\Event\Sourcing\EventStream;
use App\Common\ValueObjects\Address;
use App\Common\ValueObjects\Chain;
use App\Common\ValueObjects\Holders;
use App\Common\ValueObjects\Name;
use App\Common\ValueObjects\Percentage;
use App\Common\ValueObjects\Price;
use App\Common\ValueObjects\TransactionId;
use App\Domain\Entity\Transaction;
use App\Domain\Event\CryptocurrencyTransactionCached;


class CryptocurrencyTransaction extends EventSourcedAggregateRoot implements Transaction
{
    private TransactionId $id;
    private Address $address;
    private Name $name;
    private Price $price;
    private Chain $chain;
    private ?Holders $holders;
    private ?Percentage $percentage;
    private $published = false;
    private DateTimeImmutable $created;
    private bool $isComplete;
    private bool $isBlacklisted;
    private int $Repetitions;

    private function __construct(TransactionId $transactionId)
    {
        $this->id = $transactionId;
    }

    public static function writeNewFrom(Address $address, Name $name, Price $price, Chain $chain): self
    {

        $transactionId = TransactionId::create();
        $cryptocurrencyTransaction = new static($transactionId);
echo'hhehehe';
        $cryptocurrencyTransaction->recordThat(new CryptocurrencyTransactionCached(
                $transactionId,
                $address,
                $name,
                $chain,
                $price,
            )
        );

        return $cryptocurrencyTransaction;
    }

    public static function reconstitute(
        EventStream $history
    ): EventSourcedAggregateRoot
    {
        $cryptocurrencyTransaction = new static(new TransactionId($history->getAggregateId()));
        $cryptocurrencyTransaction->replay($history);

        return $cryptocurrencyTransaction;
    }

    public function applyCryptocurrencyTransactionCached(CryptocurrencyTransactionCached $event): void
    {
        $this->id = $event->id();
        $this->address = $event->address();
        $this->name = $event->name();
        $this->chain = $event->chain();
        $this->price = $event->price();
    }

    public function id(): TransactionId
    {
        return $this->id;
    }

    public function address(): Address
    {
        return $this->address;
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
}