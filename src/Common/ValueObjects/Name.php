<?php

namespace App\Common\ValueObjects;

use App\Domain\Entity\Names;
use InvalidArgumentException;

class Name
{
    public string $name;

    private function __construct(
        string $name
    ) {
        $this->ensureTokenNameIsNotBlacklisted($name);
        $name = $this->ensureIsLowerLetter($name);
        $this->name = $name;
    }

    public static function fromString(
        string $name
    ): self {
        return new self($name);
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function ensureIsLowerLetter(
        string $str
    ): string {
        return strtolower($str);
    }

    private function ensureTokenNameIsNotBlacklisted(
        string $name
    ): void {

        if (in_array(trim(strtolower($name)), NAMES::BLACKLISTED_NAMES_FOR_CRYPTOCURRENCIES)) {
            throw new InvalidArgumentException('Currency is on the blacklist ' . PHP_EOL);
        }
    }

    public function asString(): string
    {
        return $this->name;
    }
}
