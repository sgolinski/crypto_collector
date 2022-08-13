<?php

declare(strict_types=1);

namespace Domain\EventHandler;

use Domain\Event\DomainEvent;

interface EventHandler
{
    public function handle(DomainEvent $event): void;
}
