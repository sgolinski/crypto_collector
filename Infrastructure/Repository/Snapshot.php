<?php

namespace Infrastructure\Repository;

use Domain\Event\AggregateRoot;

class Snapshot
{
    private AggregateRoot $aggregate;
    private int $version;

    public function __construct(AggregateRoot $aggregate, int $version)
    {
        $this->aggregate = $aggregate;
        $this->version = $version;
    }

    public function aggregate(): AggregateRoot
    {
        return $this->aggregate;
    }

    public function version(): int
    {
        return $this->version;
    }
}
