<?php

namespace App\Domain\Subscribers;

interface Subscriber
{
    public function isSubscribedTo(DomainEvent $event): bool;
    public function handle(DomainEvent $event): void;
}
