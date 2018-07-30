<?php
// Load composer
require __DIR__ . '/vendor/autoload.php';

$bot_api_key  = \getenv('token');
$bot_username = \getenv('name');
$hook_url     = \getenv('url');

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    // Set webhook
    $result = $telegram->setWebhook($hook_url);
    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // log telegram errors
    // echo $e->getMessage();
}