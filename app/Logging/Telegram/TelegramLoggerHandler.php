<?php

namespace App\Logging\Telegram;

use App\Services\Telegram\TelegramBotApi;
use Monolog\Logger;

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
        TelegramBotApi::sendMessage(
            $this->token,
            $this->chatID,
            $record['formatted']
        );
    }
}
