<?php

namespace CJMustard1452\PocketCord\Webhook;

use CJMustard1452\PocketCord\Database\WebhookQueries;
use CJMustard1452\PocketCord\Discord\DiscordAPI;

class WebhookAPI {

     /** @var Webhook[] */
    public static Array $webhooks = [];

    public static Array $messageCache = [];

    public const JOIN = 'joins';
    public const LEAVE = 'leaves';
    public const CHAT = 'chats';
    public const COMMAND = 'commands';
    public const KILL = 'kills';
    public const DEATH = 'deaths';
    public const START = 'starts';
    public const STOP = 'stops';

    public static function createWebhook(String $name, String $webhookURL, ?String $imageURL = "", ?Array $tasks = []): Bool {
        if(self::getWebhook($name)) return false;

        self::$webhooks[] = new Webhook($name, $webhookURL, $imageURL, $tasks);
        WebhookQueries::createWebhook($name, $webhookURL, $imageURL, $tasks);

        return true;
    }

    public static function deleteWebhook(String $name): Bool {
        if(!$webhookObject = self::getWebhook($name)) return false;

        unset(self::$webhooks[array_search($webhookObject, self::$webhooks)]);
        WebhookQueries::deleteWebhook($name);

        return false;
    }

     /** @var Webhook[] */
    public static function getFromTask(String $task): Array {
        $webhooks = [];

        foreach(self::$webhooks as $webhookObject) {
            if(!in_array($task, $webhookObject->tasks)) continue;
            $webhooks[] = $webhookObject;
        }

        return $webhooks;
    }

    public static function updateWebhookName(String $oldName, String $newName): Bool {
        if(!$webhookObject = self::getWebhook($oldName)) return false;

        $webhookObject->name = $newName;
        WebhookQueries::updateWebhookName($oldName, $newName);

        return true;
    }

    public static function updateWebhookURL(String $name, String $webhookURL): Bool {
        if(!$webhookObject = self::getWebhook($name)) return false;

        $webhookObject->webhookURL = $webhookURL;
        WebhookQueries::updateWebhookURL($name, $webhookURL);

        return true;
    }

    public static function updateWebhookImageURL(String $name, String $imageURL): Bool {
        if(!$webhookObject = self::getWebhook($name)) return false;

        $webhookObject->imageURL = $imageURL;
        WebhookQueries::updateWebhookImageURL($name, $imageURL);

        return true;
    }


    public static function updateWebhookTasks(String $name, Array $tasks): Bool {
        if(!$webhookObject = self::getWebhook($name)) return false;

        $webhookObject->tasks = $tasks;
        WebhookQueries::updateWebhookTasks($name, $tasks);

        return true;
    }

    public static function getWebhook(String $name): Webhook | Bool {
        foreach(self::$webhooks as $webhookObject) {
            if($webhookObject->name !== $name) continue;
            return $webhookObject;
        }

        return false;
    }

    public static function disbatchWebhooks() {
        $webhooks = WebhookMessage::getMessages();

        foreach($webhooks as $name => $data) {
            if(!$webhookObject = WebhookAPI::getWebhook($name)) continue;

            DiscordAPI::sendFromObject($webhookObject, implode("\n", $data));

            WebhookMessage::clearMessages();
        }
    }

    public function __construct() {
        if(!$webhooks = WebhookQueries::getWebhooks()) return;
        foreach($webhooks as $webhookData) {
            $name = $webhookData['webhookName'];
            $webhookURL = $webhookData['webhookURL'];
            $imageURL = $webhookData['imageURL'];

            $taskArray = [];
            if($webhookData['chats'] == 1) $taskArray[] = self::CHAT;
            if($webhookData['commands'] == 1) $taskArray[] = self::COMMAND;
            if($webhookData['joins'] == 1) $taskArray[] = self::JOIN;
            if($webhookData['leaves'] == 1) $taskArray[] = self::LEAVE;
            if($webhookData['kills'] == 1) $taskArray[] = self::KILL;
            if($webhookData['deaths'] == 1) $taskArray[] = self::DEATH;
            if($webhookData['starts'] == 1) $taskArray[] = self::START;
            if($webhookData['stops'] == 1) $taskArray[] = self::STOP;

            self::$webhooks[] = new Webhook($name, $webhookURL, $imageURL, $taskArray);
        }
    }
}