<?php

namespace App\Domain\Event;

use App\Common\Event\DomainEvent;
use DateTimeImmutable;

class PotentialDumpAndPumpRecognized implements DomainEvent
{
    private int $repetitions;

    public function __construct(int $repetitions)
    {
        $this->repetitions = $repetitions;
    }

    public function repetitions(): int
    {
        return $this->repetitions;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }
}
