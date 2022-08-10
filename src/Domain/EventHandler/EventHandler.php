<?php

declare(strict_types=1);

namespace App\Domain\EventHandler;

use App\Common\Event\DomainEvent;

interface EventHandler
{
    public function handle(DomainEvent $event): void;

    public function supports(DomainEvent $event): bool;
}
