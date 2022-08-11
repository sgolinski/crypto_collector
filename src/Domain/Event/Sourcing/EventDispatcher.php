<?php

namespace App\Domain\Event\Sourcing;

interface EventDispatcher
{
    public function dispatch(DispatchableDomainEvent $aDispatchableDomainEvent);

    public function registerEventDispatcher(EventDispatcher $anEventDispatcher);

    public function understands(DispatchableDomainEvent $aDispatchableDomainEvent);
}
