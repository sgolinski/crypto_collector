<?php

namespace Domain\ValueObjects;


class Id
{
    private string $id;

    private function __construct(
        string $address
    )
    {
        $this->id = trim(str_replace('/address/', '', $address));
    }

    public static function fromString(string $id): Id
    {
        return new static($id);
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
