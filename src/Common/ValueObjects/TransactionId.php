<?php

namespace App\Common\ValueObjects;

use Ramsey\Uuid\Uuid;

class TransactionId
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function fromString(string $id): TransactionId
    {
        return new static($id);
    }

    public static function create(): TransactionId
    {
        return new static(Uuid::uuid4()->toString());
    }

    public function id(): string
    {
        return $this->id;
    }

    public function asString(): string
    {
        return $this->id;
    }
}
