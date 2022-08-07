<?php

namespace App\Domain\Entity;

use App\Common\ValueObjects\Chain;
use App\Common\ValueObjects\Price;
use InvalidArgumentException;

class Information
{
    public array $information;
    public Chain $chain;
    public Price $price;

    private function __construct(
        string $information
    )
    {
        $this->ensureInformationIsNotNull($information);
        $this->ensureInformationAfterExplodeHasTwoEntry($information);
        $this->information = explode(" ", $information);
        $this->ensureInformationAboutPriceIsNotNull($this->information[0]);
        $this->price = $this->extractPriceFrom($this->information[0]);
        $this->ensureInformationAboutTokenIsNotNull($this->information[1]);
        $this->chain = $this->extractChainFrom($this->information[1]);
        $this->ensureIsAllowedChain($this->chain);
    }

    public static function fromString(
        string $information
    ): self
    {
        return new self($information);
    }

    private function ensureInformationIsNotNull(
        string $information
    ): void
    {
        if ($information === null) {
            throw new InvalidArgumentException('Information is empty!');
        }
    }

    private function ensureInformationAfterExplodeHasTwoEntry(string $information): void
    {
        if (count(explode(" ", $information)) < 2) {
            throw new InvalidArgumentException('Information data has not allowed format');
        }
    }

    public function getChain(): Chain
    {
        return $this->chain;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    private function extractPriceFrom(
        string $float
    ): Price
    {
        $strPrice = str_replace([','], [''], $float);

        return Price::fromFloat(round((float)$strPrice, 3));
    }

    private function extractChainFrom(
        string $data
    ): Chain
    {
        return Chain::fromString(strtolower($data));
    }

    private function ensureInformationAboutPriceIsNotNull(mixed $int)
    {
        if ($int === null) {
            throw new InvalidArgumentException('Information about price is missing');
        }
    }

    private function ensureInformationAboutTokenIsNotNull(mixed $int)
    {
        if ($int === null) {
            throw new InvalidArgumentException('Information about token is missing');
        }
    }

    private function ensureIsAllowedChain(Chain $chain): void
    {
        if (!in_array($chain->__toString(), Names::ALLOWED_NAMES_FOR_CHAINS)) {
            throw new InvalidArgumentException('Currency not allowed');
        }
    }


}
