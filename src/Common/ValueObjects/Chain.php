<?php

namespace App\Common\ValueObjects;

use App\Domain\Entity\Names;
use InvalidArgumentException;

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
        return new self(strtolower($chain));
    }

    public function asString(): string
    {
        return $this->chain;
    }


}
