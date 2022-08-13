<?php

namespace Domain\ValueObjects;


use Domain\Entity\Names;
use InvalidArgumentException;


class Chain
{
    private string $chain;

    private function __construct(
        string $chain
    ) {
        $this->ensureIsAllowedChain($chain);
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

    private function ensureIsAllowedChain(string $chain): void
    {
        if (!in_array(strtolower($chain), Names::ALLOWED_NAMES_FOR_CHAINS)) {
            throw new InvalidArgumentException('Currency not allowed');
        }
    }

}
