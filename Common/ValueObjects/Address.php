<?php

namespace App\Common\ValueObjects;

class Address
{
    public string $address;

    private function __construct(
        string $address
    ) {
        $this->address = trim(str_replace('/address/', '', $address));
    }

    public static function fromString(
        string $address
    ): self {
        return new self($address);
    }

    public function __toString(): string
    {
        return $this->address;
    }
}
