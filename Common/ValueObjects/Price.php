<?php

namespace App\Common\ValueObjects;

class Price
{
    public float $price;

    private function __construct(
        float $price
    ) {
        $this->price = $price;
    }

    public static function fromFloat(
        float $price
    ): self {
        return new self($price);
    }

    public function asFloat(): float
    {
        return $this->price;
    }

    public function __toString(): string
    {
        return $this->price;
    }
}
