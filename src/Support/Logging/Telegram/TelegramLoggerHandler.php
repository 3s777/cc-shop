<?php

namespace Support\Logging\Telegram;


use Monolog\Logger;
use Services\Telegram\TelegramBotApi;
use Services\Telegram\TelegramBotApiContract;

class TelegramLoggerHandler extends \Monolog\Handler\AbstractProcessingHandler
{
    protected  int $chatID;

    protected  string $token;

    public function __construct(array $config) {

        $level = Logger::toMonologLevel($config['level']);

        parent::__construct($level);

        $this->chatID = (int) $config['chat_id'];
        $this->token = $config['token'];
    }

    /**
     * @param array{"message": string, "context": array, "level": int}
     * @return void
     */
    protected function write(array $record): void
    {
        app(TelegramBotApiContract::class)::sendMessage(
            $this->token,
            $this->chatID,
            $record['formatted']
        );
    }
}
