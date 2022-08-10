<?php

namespace App\Common\Event\Sourcing;

interface EventNotifiable
{
    public function notifyDispatchableEvents();
}
