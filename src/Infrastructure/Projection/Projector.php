<?php

namespace App\Infrastructure\Projection;

use App\Projection;

class Projector
{
    /** @var Projection[] */
    private array $projections = [];

    /** @param Projection[] $projections */
    public function register(array $projections): void
    {
        foreach ($projections as $projection) {
            $this->projections[$projection->listensTo()] = $projection;
        }
    }

    /** @param DomainEvent[] $events */
    public function project(array $events): void
    {
        foreach ($events as $event) {
            if (isset($this->projections[get_class($event)])) {
                $this->projections[get_class($event)]->project($event);
            }
        }
    }
}
