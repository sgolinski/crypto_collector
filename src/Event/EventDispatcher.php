<?php

namespace App\Event;

interface EventDispatcher
{

    public function dispatchAll(array $recordedEvents);
}