<?php

declare(strict_types = 1);

include('../src/common.php');

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$parsedRequestUri = trim($_SERVER['REQUEST_URI'], '/');
if (array_key_exists($parsedRequestUri, BOT_TOKENS)) {
    $currentBot = BOT_TOKENS[$parsedRequestUri];

    $logger = new Logger($currentBot);
    $streamHandler = new StreamHandler('telegramApiLogs/main.log');
    #$streamHandler->setLevel(Logger::INFO);
    $logger->pushHandler($streamHandler);

    $logger->addDebug('--------------------------------');
    $logger->addInfo(sprintf('New request on bot %s', $currentBot));
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

    try {
        $completeName = 'unreal4u\\TelegramBots\\Bots\\' . $currentBot;
        /** @var $bot \unreal4u\TelegramBots\Bots\Base */
        $bot = new $completeName($logger, $parsedRequestUri);
        $logger->debug('Incoming data', [$_POST]);
        $response = $bot->run($_POST);
        $bot->sendResponseBack($response);
    } catch (\Exception $e) {
        $logger->addError(sprintf('Captured exception: "%s"', $e->getMessage()));
    }
} else {
    header('Location: https://github.com/unreal4u?tab=repositories', true, 302);
}

