<?php

namespace App\Domain\Action;

use App\Common\ValueObjects\Url;
use App\Domain\Entity\Urls;
use App\Infrastructure\Repository\PantherCryptocurrencyRepository;

class StartCollectingTransactions
{
    private string $url;

    private int $currentSite = 0;

    public function __construct()
    {
        $this->url = Urls::URL_CON;
    }

    public function url(): Url
    {
        return Url::fromString($this->url . $this->currentSite);
    }

    public function goToNext(): void
    {
        $this->currentSite++;
    }
}
