<?php

namespace App\Domain\Query;

use Facebook\WebDriver\Remote\RemoteWebElement;

class FindCryptocurrencyTransactionInWebElement
{
    private RemoteWebElement $element;

    public function __construct(RemoteWebElement $element)
    {
        $this->element = $element;
    }

    public function element(): RemoteWebElement
    {
        return $this->element;
    }
}