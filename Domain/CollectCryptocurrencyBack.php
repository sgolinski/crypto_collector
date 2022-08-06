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

class CollectCryptocurrencyBack extends CrawlerDexTracker implements Crawler
{
    public function invoke(): void
    {
        try {
            echo "Start crawling " . date("F j, Y, g:i:s a") . PHP_EOL;
            $this->startClient(Urls::URL);
            //$this->changeOnWebsiteToShowMoreRecords();
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
        $lastUri = '';
        for ($i = 0; $i < 200; $i++) {
            $currentUri = Urls::URL_CON . $i;
            echo 'Start getting content for page ' . $i . ' ' . date("F j, Y, g:i:s a") . PHP_EOL;
            try {
                $data = $this->getElementsFromWebsite($currentUri);
                if ($currentUri === $lastUri) {
                    continue;
                }
                echo $this->client->getCrawler()->getUri() . PHP_EOL;
                $this->createCryptocurrencyFrom($data);
                $lastUri = $currentUri;
                echo 'Finish getting content for page ' . $i . ' ' . date("F j, Y, g:i:s a") . PHP_EOL;

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

    private function getElementsFromWebsite($url): ?ArrayIterator
    {
        $this->client->get($url);
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
    ): void {
        foreach ($webElements as $webElement) {
            try {
                assert($webElement instanceof RemoteWebElement);

                $information = $this->getInformationFrom($webElement);

                $service = Information::fromString($information);
                $chain = $service->getChain();
                $price = $service->getPrice();


                $name = $this->getNameFrom($webElement);
                $name = Name::fromString($name);
                $this->ensureTokenNameIsNotBlacklisted($name);

                $cryptocurrencyExist = $this->findCryptocurrencyBy($name);

                if ($cryptocurrencyExist) {
                    continue;
                }
                $address = $this->getAddressFrom($webElement);
                $address = Address::fromString($address);

                $this->emmitCryptocurrencyRegisterEvent($address, $name, $price, $chain);
            } catch (InvalidArgumentException) {
                continue;
            }
        }
    }

    private function findCryptocurrencyBy(Name $name): bool
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


    private function ensureTokenNameIsNotBlacklisted(
        string $name
    ): void {
        if (in_array($name, NAMES::BLACKLISTED_NAMES_FOR_CRYPTOCURRENCIES)) {
            throw new InvalidArgumentException('Currency is on the blacklist');
        }
    }

    /**
     * @param RemoteWebElement $webElement
     * @return string
     */
    private function getNameFrom(RemoteWebElement $webElement): string
    {
        return $webElement
            ->findElement(WebDriverBy::cssSelector(ScriptsJs::NAME_SELECTOR))
            ->getText();
    }

    /**
     * @param RemoteWebElement $webElement
     * @return string
     */
    private function getInformationFrom(RemoteWebElement $webElement): string
    {
        return $webElement
            ->findElement(WebDriverBy::cssSelector(ScriptsJs::INFORMATION_SELECTOR))
            ->getText();
    }

    /**
     * @param RemoteWebElement $webElement
     * @return string|true|null
     */
    private function getAddressFrom(RemoteWebElement $webElement): string|bool|null
    {
        return $webElement
            ->findElement(WebDriverBy::cssSelector(ScriptsJs::ADDRESS_SELECTOR))
            ->getAttribute('href');
    }
}
