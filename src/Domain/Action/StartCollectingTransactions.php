<?php

namespace App\Domain\Action;

use App\Domain\Entity\Urls;
use App\Domain\ValueObjects\Url;

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
