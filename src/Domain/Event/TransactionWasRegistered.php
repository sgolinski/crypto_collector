<?php

namespace App\Domain\Event;

use App\Common\Event\DomainEvent;
use App\Common\ValueObjects\Address;
use App\Common\ValueObjects\Id;
use App\Common\ValueObjects\Url;
use App\Domain\Entity\Urls;
use DateTimeImmutable;

class TransactionWasRegistered implements DomainEvent
{
    private Id $id;
    private Url $url;

    public function __construct($id)
    {
        $this->url = Url::fromString(Urls::URL_TOKEN . $id->asString());
        $this->id = $id;
    }

    public function url(): Url
    {
        return $this->url;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }

    public function id(): Id
    {
        return $this->id;
    }
}
