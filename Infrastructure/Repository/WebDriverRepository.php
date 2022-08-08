<?php

namespace App\Infrastructure\Repository;

use App\Common\ValueObjects\Address;
use App\Common\ValueObjects\Chain;
use App\Common\ValueObjects\Name;
use App\Common\ValueObjects\Price;
use App\Domain\CryptocurrencyTransaction;
use App\Domain\Entity\ScriptsJs;
use Exception;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;

class WebDriverRepository
{
    public function findTransactionByWebElement(RemoteWebElement $webElement): ?CryptocurrencyTransaction
    {
        try {


        return CryptocurrencyTransaction::writeNewFrom(
            $this->findAddress($webElement),
            $this->findName($webElement),
            $this->findPrice($webElement),
            $this->findChain($webElement)
        );
            }catch (Exception $exception)
        {
            echo $exception->getMessage();
        }
        return null;
    }

    private function findName(RemoteWebElement $webElement): Name
    {
        return Name::fromString($webElement
            ->findElement(WebDriverBy::cssSelector(ScriptsJs::NAME_SELECTOR))
            ->getText());
    }

    private function findPrice(RemoteWebElement $webElement): Price
    {
        return $this->extractInformation($webElement)[0];
    }

    private function findChain(RemoteWebElement $webElement): Chain
    {
        return $this->extractInformation($webElement)[1];
    }

    private function findAddress(RemoteWebElement $webElement): Address
    {
        return Address::fromString($webElement
            ->findElement(WebDriverBy::cssSelector(ScriptsJs::ADDRESS_SELECTOR))
            ->getAttribute('href'));
    }

    private function extractInformation(RemoteWebElement $webElement):array
    {

        $information = $webElement
            ->findElement(WebDriverBy::cssSelector(ScriptsJs::INFORMATION_SELECTOR))
            ->getText();

        $information = explode(" ", $information);

       return [$this->extractPriceFrom($information[0]), $this->extractChainFrom($information[0])];
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
}