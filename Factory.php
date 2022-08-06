<?php

namespace App;

use Facebook\WebDriver\WebDriverElement;
use Facebook\WebDriver\WebDriverSelect;
use Maknz\Slack\Client as SlackClient;
use Maknz\Slack\Message;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\DomCrawler\Crawler;


class Factory
{

    /**
     * @throws \Facebook\WebDriver\Exception\UnexpectedTagNameException
     */
    public static function createWebDriverSelect(
        WebDriverElement $element
    ): WebDriverSelect
    {
        return new WebDriverSelect($element);
    }

    public static function createPantherClient(): Client
    {
        return Client::createChromeClient();
    }

    public static function createSlackMessage(): Message
    {
        return new Message();
    }

    public static function createSlackClient(string $hook): SlackClient
    {
        return new SlackClient($hook);
    }

}