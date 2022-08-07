<?php

namespace App\Domain\Entity;

use App\Common\Event\EventSourcedAggregateRoot;
use App\Common\Event\Sourcing\EventStream;
use App\Common\ValueObjects\Address;
use App\Common\ValueObjects\Chain;
use App\Common\ValueObjects\CryptocurrencyId;
use App\Common\ValueObjects\Holders;
use App\Common\ValueObjects\Name;
use App\Common\ValueObjects\Percentage;
use App\Common\ValueObjects\Price;
use App\Domain\Event\CryptocurrencyPercentageWasChanged;
use App\Domain\Event\CryptocurrencyPriceWasChanged;
use App\Domain\Event\CryptocurrencyWasRegistered;
use DateTimeImmutable;

class Token extends EventSourcedAggregateRoot implements Cryptocurrency
{
    private CryptocurrencyId $id;
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

    private function __construct(CryptocurrencyId $cryptocurrencyId)
    {
        $this->id = $cryptocurrencyId;
    }

    public static function writeNewFrom(
        $address,
        $name,
        $price,
        $chain,
    ): self
    {
        $cryptocurrencyId = CryptocurrencyId::create();
        $cryptocurrency = new static($cryptocurrencyId);

        $cryptocurrency->recordApplyAndPublishThat(
            new CryptocurrencyWasRegistered(
                $cryptocurrencyId,
                $address,
                $name,
                $price,
                $chain
            )
        );

        return $cryptocurrency;
    }

    public function fromParams(
        Address           $address,
        Name              $name,
        Chain             $chain,
        Price             $price,
        DateTimeImmutable $created,
        bool              $isComplete,
        bool              $isBlacklisted,
        Holders           $holders = null,
    ): self
    {
        $this->address = $address;
        $this->name = $name;
        $this->chain = $chain;
        $this->price = $price;
        $this->created = $created;
        $this->isComplete = $isComplete;
        $this->isBlacklisted = $isBlacklisted;
        $this->holders = $holders;

        return $this;
    }

    public static function create(
        $cryptocurrencyId
    ): self
    {
        return new static($cryptocurrencyId);
    }

    public function changePriceFor($price): void
    {
        $this->recordApplyAndPublishThat(
            new CryptocurrencyPriceWasChanged($this->id, $price)
        );
    }

    public function id(): CryptocurrencyId
    {
        return $this->id;
    }

    protected function applyCryptocurrencyWasRegistered(
        CryptocurrencyWasRegistered $event
    ): void
    {
        $this->address = $event->address();
        $this->name = $event->name();
        $this->price = $event->price();
        $this->chain = $event->chain();
    }


    protected function applyCryptocurrencyPercentageWasChanged(
        CryptocurrencyPercentageWasChanged $event
    ): void
    {
        $this->percentage = $event->percentage();
    }

    protected function applyCryptocurrencyPriceWasChanged(
        CryptocurrencyPriceWasChanged $event
    ): void
    {
        $this->price = $event->price();
    }

    public static function reconstitute(
        EventStream $history
    ): EventSourcedAggregateRoot
    {
        $cryptocurrency = new static(new CryptocurrencyId($history->getAggregateId()));
        $cryptocurrency->replay($history);

        return $cryptocurrency;
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
