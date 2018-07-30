#!/usr/bin/env php
<?php
declare(strict_types=1);

use Desperado\TelegramBot\Application\Builder\Bootstrap;
use Desperado\TelegramBot\Application\Builder\ApiClientBuilder;
use Desperado\TelegramBot\Domain;
use function Amp\Promise\wait;

require __DIR__ . '/vendor/autoload.php';
try {
    $bootstrap = new Bootstrap(__DIR__ . '/.env');
    $apiClient = (new ApiClientBuilder($bootstrap))
        ->build(Domain\Bot\TelegramBot::createFromEnvironment());
    $command = new Domain\Methods\SetWebhook(
        \str_replace(
            '{token}',
            \getenv('TELEGRAM_BOT_ACCESS_TOKEN'),
            \getenv('NOTIFICATIONS_URL')
        ),
        \getenv('NOTIFICATIONS_CERT_PATH')
    );
    /** @var Domain\Types\SimpleResponse $response */
    $response = wait(
        $apiClient->do($command)
    );
    echo \sprintf('%s%s', $response->getDescription(), \PHP_EOL);
} catch (\Throwable $throwable) {
    echo $throwable->getMessage() . \PHP_EOL;
}