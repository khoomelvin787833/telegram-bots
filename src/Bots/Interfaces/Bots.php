<?php

namespace unreal4u\TelegramBots\Bots\Interfaces;

use Psr\Log\LoggerInterface;
use GuzzleHttp\Client;
use unreal4u\TelegramAPI\Abstracts\TelegramMethods;

interface Bots
{
    /**
     * Bots constructor.
     * @param LoggerInterface $logger
     * @param string $token
     * @param Client|null $client
     */
    public function __construct(LoggerInterface $logger, string $token, Client $client = null);

    public function run(array $postData = []): TelegramMethods;
}
