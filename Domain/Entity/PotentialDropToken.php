<?php

namespace App\Domain\Entity;

use App\Common\Event\EventSourcedAggregateRoot;
use App\Common\Event\Sourcing\EventStream;
use App\Common\ValueObjects\Address;
use App\Common\ValueObjects\Chain;
use App\Common\ValueObjects\CryptocurrencyId;
use App\Common\ValueObjects\Name;
use App\Common\ValueObjects\Price;
use App\Domain\CollectCryptocurrency;
use App\Domain\Event\CryptocurrencyPercentageWasChanged;
use App\Domain\Event\CryptocurrencyPriceWasChanged;
use App\Domain\Event\CryptocurrencyWasRegistered;

class PotentialDropToken extends EventSourcedAggregateRoot implements Cryptocurrency
{
    private Name $name;
    private Address $address;
    private int $repeats = 0;
    private array $prices = [];

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
        $cryptocurrency->prices[] = $price->asFloat();

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

    public function name(): Name
    {
        return $this->name;
    }

    public function address(): Address
    {
        return $this->address;
    }

    public function noticeRepeat(): self
    {
        if ($this->repeats > 10) {
            $this->repeats = 0;
            CollectCryptocurrency::EmmitPotentialDropEvent($this);
        }
        $this->repeats++;
        return $this;
    }

    public function getRepeats(): int
    {
        return $this->repeats;
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


    public function price(): Price
    {
        return $this->price;
    }

    public function chain(): Chain
    {
        return $this->chain;
    }

    private function addPrices(Price $price): void
    {
        $this->prices[] = $price->asFloat();
    }

}