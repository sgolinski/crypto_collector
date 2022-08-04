<?php

namespace App\Common\ValueObjects;

class Chain
{
    private string $chain;

    private function __construct(
        string $chain
    ) {
        $this->chain = $chain;
    }

    public static function fromString(
        string $chain
    ): self {
        return new self($chain);
    }

    public function __toString(): string
    {
        return $this->chain;
    }
}
