<?php

namespace App\Common\ValueObjects;

use Ramsey\Uuid\Uuid;

class CryptocurrencyId
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function fromString(string $id): CryptocurrencyId
    {
        return new static($id);
    }

    public static function create(): CryptocurrencyId
    {
        return new static(Uuid::uuid4()->toString());
    }

    public function id(): string
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->id;
    }
}
