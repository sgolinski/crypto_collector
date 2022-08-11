<?php

declare(strict_types=1);

namespace App\Domain\EventHandler;

use App\Domain\Event\DomainEvent;

interface EventHandler
{
    public function handle(DomainEvent $event): void;
}
