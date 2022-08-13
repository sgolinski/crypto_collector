<?php

namespace  App\ValueObjects;

class Percentage
{
    private float $percent;

    private function __construct(
        float $percent
    ) {
        $this->percent = $percent;
    }

    public static function fromFloat(
        float $percent
    ): self {
        return new self($percent);
    }

    public function asFloat(): float
    {
        return $this->percent;
    }

    public function __toString(): string
    {
        return $this->percent;
    }
}
