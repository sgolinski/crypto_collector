<?php

namespace App\Common\ValueObjects;

class Holders
{
    public int $holders = 0;
    public const MIN_AMOUNT_HOLDERS = 1000;

    private function __construct(
        int $holders
    ) {
        $this->holders = $holders;
    }

    public static function fromInt(
        int $numOfHolders
    ): self {
        return new self($numOfHolders);
    }

    public function asInt(): int
    {
        return $this->holders;
    }
    public function __toString(): string
    {
        return $this->holders;
    }
}
