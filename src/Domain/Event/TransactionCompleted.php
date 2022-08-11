<?php

namespace App\Domain\Event;

use App\Domain\Entity\Urls;
use App\Domain\ValueObjects\Id;
use App\Domain\ValueObjects\Url;
use DateTimeImmutable;

class TransactionCompleted implements DomainEvent
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
