<?php

namespace App\Domain\Entity;

use InvalidArgumentException;

class Information
{
    private function ensureInformationIsNotNull(
        string $information
    ): void {
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
}
