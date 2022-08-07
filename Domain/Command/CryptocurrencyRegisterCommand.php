<?php

namespace App\Domain\Command;

use App\Common\ValueObjects\Address;
use App\Common\ValueObjects\Chain;
use App\Common\ValueObjects\CryptocurrencyId;
use App\Common\ValueObjects\Holders;
use App\Common\ValueObjects\Name;
use App\Common\ValueObjects\Percentage;
use App\Common\ValueObjects\Price;
use DateTimeImmutable;

class CryptocurrencyRegisterCommand
{
    public CryptocurrencyId $id;
    public Address $address;
    public Name $name;
    public Price $price;
    public Chain $chain;
    public ?Holders $holders;
    public ?Percentage $percentage;
    public DateTimeImmutable $occured_on;

    /**
     * @param Address $address
     * @param Name $name
     * @param Price $price
     * @param Chain $chain
     */
    public function __construct(Address $address, Name $name, Price $price, Chain $chain)
    {
        $this->address = $address;
        $this->name = $name;
        $this->price = $price;
        $this->chain = $chain;
    }
}
