<?php

namespace App\Infrastructure\Repository;

use App\Common\ValueObjects\Chain;
use App\Common\ValueObjects\Id;
use App\Common\ValueObjects\Name;
use App\Common\ValueObjects\Price;
use App\CryptocurrencyTransaction;
use App\Domain\Entity\ScriptsJs;
use ArrayIterator;
use Exception;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;

class CacheRepository
{
    private array $webElementCache = [];

    public function findAllTransactions(ArrayIterator $webElements)
    {
        try {
            foreach ($webElements as $cache) {
                $transaction = $this->findOneTransaction($cache);
                if ($transaction !== null) {
                    $this->webElementCache[$transaction->id()->asString()] = $transaction;
                }
            }
            return $this->webElementCache;
        } catch (Exception $exception) {

        }
    }

    public function findOneTransaction(RemoteWebElement $webElement): ?CryptocurrencyTransaction
    {
        try {
            return CryptocurrencyTransaction::writeNewFrom(
                $this->findId($webElement),
                $this->findName($webElement),
                $this->findPrice($webElement),
                $this->findChain($webElement)
            );
        } catch (Exception $exception) {

        }
        return null;
    }

    public function findOneFromCache($id)
    {
        return $this->webElementCache[$id];
    }

    private function findName(RemoteWebElement $webElement): Name
    {
        return Name::fromString($webElement
            ->findElement(WebDriverBy::cssSelector(ScriptsJs::NAME_SELECTOR))
            ->getText());
    }

    private function findId(RemoteWebElement $webElement): Id
    {
        return Id::fromString($webElement
            ->findElement(WebDriverBy::cssSelector(ScriptsJs::ADDRESS_SELECTOR))
            ->getAttribute('href'));
    }

    private function findPrice(RemoteWebElement $webElement): Price
    {
        $information = $webElement
            ->findElement(WebDriverBy::cssSelector(ScriptsJs::INFORMATION_SELECTOR))
            ->getText();


        $information = explode(" ", $information);

        return $this->extractPriceFrom($information[0]);
    }

    private function findChain(RemoteWebElement $webElement): Chain
    {
        $information = $webElement
            ->findElement(WebDriverBy::cssSelector(ScriptsJs::INFORMATION_SELECTOR))
            ->getText();

        $information = explode(" ", $information);

        return $this->extractChainFrom($information[1]);
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
