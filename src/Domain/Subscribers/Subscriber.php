<?php

namespace App\Domain\Subscribers;

use App\Common\Event\DomainEvent;

interface Subscriber
{
    public function isSubscribedTo(DomainEvent $event): bool;

    public function handle(DomainEvent $event): void;
}
