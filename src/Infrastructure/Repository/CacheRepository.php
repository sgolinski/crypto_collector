<?php

namespace App\Infrastructure\Repository;

use App\CryptocurrencyTransaction;
use App\Domain\Entity\Addresses;
use App\Domain\Entity\ScriptsJs;
use App\Domain\ValueObjects\Chain;
use App\Domain\ValueObjects\Id;
use App\Domain\ValueObjects\Name;
use App\Domain\ValueObjects\Price;
use ArrayIterator;
use Exception;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;
use InvalidArgumentException;


class CacheRepository
{
    private array $webElementCache = [];

    public function findAllTransactions(ArrayIterator $webElements)
    {
        try {
            foreach ($webElements as $cache) {
                /** @var CryptocurrencyTransaction $transaction $transaction */
                $transaction = $this->findOneTransaction($cache);

                if ($transaction !== null) {
                    $key = $transaction->id()->asString();
                    if (array_key_exists($key, $this->webElementCache)) {
                        if ($this->webElementCache[$key]->price()->asFloat() === $transaction->price()->asFloat()) {
                            continue;
                        }
                        $this->webElementCache[$key]->noticeRepetitions();
                    } else {
                        $this->webElementCache[$key] = $transaction;
                    }
                }
            }
            return $this->webElementCache;
        } catch (Exception $exception) {
            echo $exception->getMessage() . PHP_EOL;
        }
    }

    public function findOneTransaction(RemoteWebElement $webElement): ?CryptocurrencyTransaction
    {
        try {
            $transaction =  CryptocurrencyTransaction::writeNewFrom(
                $this->findId($webElement),
                $this->findName($webElement),
                $this->findPrice($webElement),
                $this->findChain($webElement)
            );
            (new RedisCryptocurrencyRepository())->save($transaction);
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

        $id = Id::fromString($webElement
            ->findElement(WebDriverBy::cssSelector(ScriptsJs::ADDRESS_SELECTOR))
            ->getAttribute('href'));

        if (in_array($id->asString(), Addresses::BLACKLISTED_ADDRESSES, true)) {
            throw new InvalidArgumentException('Blacklisted!');
        }

        return $id;
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
