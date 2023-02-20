<?php


namespace Services\Telegram;

use Illuminate\Support\Facades\Http;
use Services\Telegram\Exceptions\TelegramBotApiException;
use Services\Telegram\TelegramBotApiContract;
use Services\Telegram\TelegramBotApiFake;

class TelegramBotApi implements TelegramBotApiContract
{
    public const HOST = 'https://api.telegram.org/bot';

    public static function fake(): TelegramBotApiFake
    {
        return app()->instance(
          TelegramBotApiContract::class,
          new TelegramBotApiFake()
        );
    }

    public static function sendMessage(string $token, int $chatID, string $text): bool
    {
        try {
            $response = Http::get(self::HOST . $token. '/sendMessage', [
                'chat_id' => $chatID,
                'text' => $text
            ])->throw()->json();

            return $response['ok'] ?? false;

        } catch (\Throwable $e) {
            report(new TelegramBotApiException($e->getMessage()));

            return false;
        }
    }
}
