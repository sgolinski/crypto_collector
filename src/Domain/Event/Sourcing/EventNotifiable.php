<?php

namespace App\Domain\Event\Sourcing;

interface EventNotifiable
{
    public function notifyDispatchableEvents();
}
