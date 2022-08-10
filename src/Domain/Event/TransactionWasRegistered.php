<?php

namespace App\Domain\Event;

use App\Common\Event\DomainEvent;
use App\Common\ValueObjects\Address;
use App\Common\ValueObjects\Url;
use App\Domain\Entity\Urls;
use DateTimeImmutable;

class TransactionWasRegistered implements DomainEvent
{
    private Address $address;
    private Url $url;

    public function __construct(
        $address
    ) {
        $this->url = Url::fromString(Urls::URL_TOKEN . $address->asString());
        $this->address = $address;
    }

    public function url(): Url
    {
        return $this->url;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }

    public function address(): Address
    {
        return $this->address;
    }
}
