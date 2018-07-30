#!/usr/bin/env php
<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Amp\Promise;
use Desperado\TelegramBot\Application\Builder;
use Desperado\TelegramBot\Application\EntryPoint\Events\ReceivedMessageEvent;
use Desperado\TelegramBot\Domain\Methods\Message\SendMessage;
use Desperado\TelegramBot\Application\EntryPoint\Data\WebHooksConfig;
use Desperado\TelegramBot\Infrastructure\HttpServer;
use function Desperado\TelegramBot\Infrastructure\getEnvironmentValue;
use Desperado\TelegramBot\Domain;

try {
    $bootstrap = new Builder\Bootstrap(__DIR__ . '/.env');
    $telegramBot = Domain\Bot\TelegramBot::createFromEnvironment();
    $apiClient = (new Builder\ApiClientBuilder($bootstrap))->build($telegramBot);

    $listener = static function (ReceivedMessageEvent $event) use ($apiClient): Promise {
        /** @var \Desperado\TelegramBot\Domain\Types\Message\Message $receivedMessage */
        $receivedMessage = $event->getEventPayload();
        return $apiClient->do(
            new SendMessage(
                $receivedMessage->getChat()->getId(),
                \sprintf(
                    'Welcome. Your message: "%s"',
                    $receivedMessage->getText()
                )
            )
        );
    };
    $entryPoint = (new Builder\EntryPointBuilder($bootstrap, $apiClient))
        ->addListener(ReceivedMessageEvent::OPERATION_TITLE, $listener)
        ->build(Builder\EntryPointBuilder::WEBHOOKS_ENTRY_POINT);
    $entryPoint->run(
        $telegramBot,
        WebHooksConfig::createFromEnvironment(),
        new HttpServer\AmpHttpServer(
            HttpServer\HttpServerConfiguration::createFromEnvironment(),
            $bootstrap->getLogger(),
            getEnvironmentValue('LOG_API_INTERACTIONS', 'bool')
        )
    );
} catch (\Throwable $throwable) {
    echo Domain\formatThrowable($throwable) . \PHP_EOL;
}