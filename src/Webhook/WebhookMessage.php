<?php

namespace CJMustard1452\PocketCord\Webhook;

use CJMustard1452\PocketCord\Discord\DiscordAPI;

class WebhookMessage {

    private static array $messages = [];

    public const FORMATS = [
        WebhookAPI::CHAT => "chat-format",
        WebhookAPI::COMMAND => "command-format",
        WebhookAPI::JOIN => "join-format",
        WebhookAPI::LEAVE => "leave-format",
        WebhookAPI::DEATH => "death-format",
        WebhookAPI::KILL => "kill-format",
        WebhookAPI::START => "start-format",
        WebhookAPI::STOP => "stop-format"
    ];

    public static function getMessages(): array {
        return self::$messages;
    }

    public static function clearMessages() {
        self::$messages = [];
    }

    public static function applyMessage(String $task, String $message): bool {
        $webhooks = WebhookAPI::getFromTask($task);

        $message = self::formatTime($message);

        foreach($webhooks as $webhookObject) {
            self::$messages[$webhookObject->name][] = $message;
        }

        return true;
    }

    public static function formatTime(String $message): String {
        $time = gmdate("n/d/y h:i:sA", time());
        $message = str_replace('{time}', $time, $message);

        return $message;
    }
}