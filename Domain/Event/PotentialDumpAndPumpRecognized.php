<?php

namespace Domain\Event;

use DateTimeImmutable;

class PotentialDumpAndPumpRecognized implements DomainEvent
{
    public function occurredOn(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }
}
