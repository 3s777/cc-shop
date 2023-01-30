<?php


namespace App\Services\Telegram;


use App\Exceptions\TelegramBotApiException;
use Illuminate\Support\Facades\Http;

class TelegramBotApi
{
    public const HOST = 'https://api.telegram.org/bot';

    public static function sendMessage(string $token, int $chatID, string $text)
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
