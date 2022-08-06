<?php

namespace App\Domain;

use App\Application\Service\CrawlerDexTracker;
use App\Common\ValueObjects\Address;
use App\Common\ValueObjects\Chain;
use App\Common\ValueObjects\CryptocurrencyId;
use App\Common\ValueObjects\Name;
use App\Common\ValueObjects\Price;
use App\Domain\Command\ChangePriceCommand;
use App\Domain\Command\CryptocurrencyRegisterCommand;
use App\Domain\CommandHandler\ChangePriceCommandHandler;
use App\Domain\CommandHandler\CryptocurrencyRegisterCommandHandler;
use App\Domain\Entity\Currency;
use App\Domain\Entity\Information;
use App\Domain\Entity\Names;
use App\Domain\Entity\ScriptsJs;
use App\Domain\Entity\Urls;
use App\Domain\Model\Cryptocurrency;
use App\Domain\Query\CryptocurrencyQueryByName;
use App\Domain\QueryHandler\CryptocurrencyQueryHandlerByName;
use App\Factory;
use ArrayIterator;
use Exception;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\UnexpectedTagNameException;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;
use InvalidArgumentException;

class CollectCryptocurrency extends CrawlerDexTracker implements Crawler
{
    public function invoke(): void
    {
        try {
            echo "Start crawling " . date("F j, Y, g:i:s a") . PHP_EOL;
            $this->startClient(Urls::URL);
            $this->changeOnWebsiteToShowMoreRecords();
            usleep(3000);
            $this->scrappingData();
        } catch (Exception $exception) {
            echo $exception->getMessage() . PHP_EOL;
            $this->client->close();
            $this->client->quit();
            $this->invoke();
        }
    }

    private function scrappingData(): void
    {
        for ($i = 0; $i < 200; $i++) {
            echo 'Start getting content for page ' . $i . ' ' . date("F j, Y, g:i:s a") . PHP_EOL;
            try {
                $data = $this->getElementsFromWebsite();
                $this->client->takeScreenshot('page' . $i . '.png');
                $this->createCryptocurrencyFrom($data);
                echo 'Finish getting content for page ' . $i . ' ' . date("F j, Y, g:i:s a") . PHP_EOL;
                $nextPage = $this->client
                    ->findElement(WebDriverBy::cssSelector(ScriptsJs::BUTTON_SELECTOR));
                $nextPage->click();
                $this->client->refreshCrawler();
            } catch (Exception) {
                $this->client->reload();
                continue;
            }
        }
    }

    private function changeOnWebsiteToShowMoreRecords(): void
    {
        try {
            $selectRows = $this->client->findElement(WebDriverBy::id(ScriptsJs::SELECTOR_SELECT_MORE_RECORDS));
            $webDriverSelect = Factory::createWebDriverSelect($selectRows);
            $webDriverSelect->selectByIndex(ScriptsJs::INDEX_OF_SHOWN_ROWS);
        } catch (NoSuchElementException $exception) {
            echo $exception->getMessage();
        } catch (UnexpectedTagNameException $e) {
            echo $e->getMessage();
        }
    }

    private function getElementsFromWebsite(): ?ArrayIterator
    {
        $this->client->refreshCrawler();
        usleep(1000);
        try {
            $elements = $this->client->getCrawler()
                ->filter(ScriptsJs::CONTENT_SELECTOR_TABLE)
                ->filter(ScriptsJs::CONTENT_SELECTOR_TABLE_BODY)
                ->children()
                ->getIterator();
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
        return $elements;
    }

    private function createCryptocurrencyFrom(
        ?ArrayIterator $webElements
    ): void
    {
        foreach ($webElements as $webElement) {
            try {
                assert($webElement instanceof RemoteWebElement);
                $information = $webElement
                    ->findElement(WebDriverBy::cssSelector(ScriptsJs::INFORMATION_SELECTOR))
                    ->getText();

                $service = Information::fromString($information);

                $chain = $service->getChain();

                $this->ensureIsAllowedChain($chain);

                $price = $service->getPrice();

                $this->ensurePriceIsHighEnough($chain, $price);

                $chain = Chain::fromString($chain);

                $name = $webElement
                    ->findElement(WebDriverBy::cssSelector(ScriptsJs::NAME_SELECTOR))
                    ->getText();

                $name = Name::fromString($name);

                $this->ensureTokenNameIsNotBlacklisted($name);
                $cryptocurrency = $this->findCryptocurrencyBy($name);

                if ($cryptocurrency) {
                    continue;
                }

                $address = $webElement
                    ->findElement(WebDriverBy::cssSelector(ScriptsJs::ADDRESS_SELECTOR))
                    ->getAttribute('href');
                $address = Address::fromString($address);
                $this->emmitCryptocurrencyRegisterEvent($address, $name, $price, $chain);
            } catch (InvalidArgumentException) {
                continue;
            }
        }
    }

    private function findCryptocurrencyBy(Name $name): ?Cryptocurrency
    {
        $cryptocurrencyQuery = new CryptocurrencyQueryByName($name);
        $cryptocurrencyQueryByNameHandler = new CryptocurrencyQueryHandlerByName($this->cryptocurrencyRepository);
        $cryptocurrency = $cryptocurrencyQueryByNameHandler->__invoke($cryptocurrencyQuery);
        return $cryptocurrency;
    }

    private function emmitCryptocurrencyRegisterEvent(Address $address, Name $name, Price $price, Chain $chain): void
    {
        $registerCryptocurrencyCommand = new CryptocurrencyRegisterCommand($address, $name, $price, $chain);
        $registerCryptocurrencyCommandHandler = new CryptocurrencyRegisterCommandHandler($this->cryptocurrencyRepository);
        $registerCryptocurrencyCommandHandler->handle($registerCryptocurrencyCommand);
    }

    private function emmitCryptocurrencyPriceWasChangedEvent(CryptocurrencyId $id, Price $price): void
    {
        $changePriceCommand = new ChangePriceCommand($id, $price);
        $changePriceCommandHandler = new ChangePriceCommandHandler($this->cryptocurrencyRepository);
        $changePriceCommandHandler->handle($changePriceCommand);
    }

    private function ensureIsAllowedChain(Chain $chain): void
    {
        if (!in_array($chain->__toString(), NAMES::ALLOWED_NAMES_FOR_CHAINS)) {
            throw new InvalidArgumentException('Currency not allowed');
        }
    }

    private function ensurePriceIsHighEnough(
        Chain $chain,
        Price $price
    ): void
    {
        if ($price->asFloat() < Currency::ALLOWED_PRICE_PER_TOKEN[$chain->__toString()]) {
            throw new InvalidArgumentException('Price is not high enough');
        }
    }

    private function ensureTokenNameIsNotBlacklisted(
        string $name
    ): void
    {
        if (in_array($name, NAMES::BLACKLISTED_NAMES_FOR_CRYPTOCURRENCIES)) {
            throw new InvalidArgumentException('Currency is on the blacklist');
        }
    }
}
