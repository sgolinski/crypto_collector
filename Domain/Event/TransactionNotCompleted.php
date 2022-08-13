<?php

namespace Domain\Event;

use DateTimeImmutable;
use Domain\Entity\Urls;
use Domain\ValueObjects\Id;
use Domain\ValueObjects\Url;

class TransactionNotCompleted implements DomainEvent
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
