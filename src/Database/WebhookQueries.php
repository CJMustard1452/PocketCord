<?php

namespace CJMustard1452\PocketCord\Database;

use CJMustard1452\PocketCord\Webhook\WebhookAPI;
use SQLite3;

class WebhookQueries {

    private static SQLite3 $database;

    public function __construct(SQLite3 $database) {
        self::$database = $database;

        $initStatementWebhooks = 
            <<<EOF
                create table if not exists webhooks (
                    webhookName text not null,
                    webhookURL text not null,
                    imageURL text not null,
                    chats int not null,
                    commands int not null,
                    joins int not null,
                    leaves int not null,
                    kills int not null,
                    deaths int not null,
                    starts int not null,
                    stops int not null
                );
            EOF;
    
        self::$database->exec($initStatementWebhooks);
    }

    public static function createWebhook(String $name, String $WebhookURL, ?String $imageURL = null, ?Array $tasks = null): bool {
        $name = SQLite3::escapeString($name);
        $WebhookURL = SQLite3::escapeString($WebhookURL);

        $chats = SQLite3::escapeString(intval(in_array(WebhookAPI::CHAT, $tasks)));
        $commands = SQLite3::escapeString(intval(in_array(WebhookAPI::COMMAND, $tasks)));
        $joins = SQLite3::escapeString(intval(in_array(WebhookAPI::JOIN, $tasks)));
        $leaves = SQLite3::escapeString(intval(in_array(WebhookAPI::LEAVE, $tasks)));

        $deaths = SQLite3::escapeString(intval(in_array(WebhookAPI::DEATH, $tasks)));
        $kills = SQLite3::escapeString(intval(in_array(WebhookAPI::KILL, $tasks)));

        $stops = SQLite3::escapeString(intval(in_array(WebhookAPI::START, $tasks)));
        $starts = SQLite3::escapeString(intval(in_array(WebhookAPI::STOP, $tasks)));

        $updateWebhookData = 
        <<<EOF
           INSERT INTO webhooks (
                webhookName,
                webhookURL,
                imageURL,
                chats,
                commands,
                joins,
                leaves,
                kills,
                deaths,
                starts,
                stops
            ) VALUES (
                "$name",
                "$WebhookURL",
                "$imageURL",
                $chats,
                $commands,
                $joins,
                $leaves,
                $deaths,
                $kills,
                $starts,
                $stops
            )
        EOF;

        return self::$database->exec($updateWebhookData);
    }

    public static function deleteWebhook(String $name): bool {
        $name = SQLite3::escapeString($name);

        $updateWebhookData = 
        <<<EOF
           delete from webhooks where webhookName="$name";
        EOF;

        return self::$database->exec($updateWebhookData);
    }

    public static function getWebhooks(): array | bool {
        $getWebhookData = 
        <<<EOF
           select * from webhooks;
        EOF;
        $webhookQuery = self::$database->query($getWebhookData);
        if(!$webhookQuery) return false;
        
        $webhookArray = [];
        while ($webhookData = $webhookQuery->fetchArray(SQLITE3_ASSOC)) {
            $webhookArray[] = $webhookData;
        }

        return $webhookArray;    
    }

    public static function updateWebhookName(String $oldName, String $newName): bool {
        $oldName = SQLite3::escapeString($oldName);
        $newName = SQLite3::escapeString($newName);

        $updateWebhookData = 
        <<<EOF
           update webhooks set webhookName="$newName" where webhookName="$oldName";
        EOF;

        return self::$database->exec($updateWebhookData);
    }

    public static function updateWebhookURL(String $name, String $webhookURL): bool {
        $name = SQLite3::escapeString($name);
        $webhookURL = SQLite3::escapeString($webhookURL);

        $updateWebhookData = 
        <<<EOF
           update webhooks set webhookURL="$webhookURL" where webhookName="$name";
        EOF;

        return self::$database->exec($updateWebhookData);
    }

    public static function updateWebhookImageURL(String $name, String $imageURL): bool {
        $name = SQLite3::escapeString($name);
        $imageURL = SQLite3::escapeString($imageURL);

        $updateWebhookData = 
        <<<EOF
           update webhooks set imageURL="$imageURL" where webhookName="$name";
        EOF;

        return self::$database->exec($updateWebhookData);
    }

    public static function updateWebhookTasks(String $name, Array $tasks): bool {
        $name = SQLite3::escapeString($name);

        $chats = SQLite3::escapeString(intval(in_array(WebhookAPI::CHAT, $tasks)));
        $commands = SQLite3::escapeString(intval(in_array(WebhookAPI::COMMAND, $tasks)));
        $joins = SQLite3::escapeString(intval(in_array(WebhookAPI::JOIN, $tasks)));
        $leaves = SQLite3::escapeString(intval(in_array(WebhookAPI::LEAVE, $tasks)));

        $deaths = SQLite3::escapeString(intval(in_array(WebhookAPI::DEATH, $tasks)));
        $kills = SQLite3::escapeString(intval(in_array(WebhookAPI::KILL, $tasks)));

        $stops = SQLite3::escapeString(intval(in_array(WebhookAPI::START, $tasks)));
        $starts = SQLite3::escapeString(intval(in_array(WebhookAPI::STOP, $tasks)));

        $updateWebhookData = 
        <<<EOF
           update webhooks set chats="$chats" where webhookName="$name";
           update webhooks set commands="$commands" where webhookName="$name";
           update webhooks set joins="$joins" where webhookName="$name";
           update webhooks set leaves="$leaves" where webhookName="$name";
           update webhooks set deaths="$deaths" where webhookName="$name";
           update webhooks set kills="$kills" where webhookName="$name";
           update webhooks set starts="$starts" where webhookName="$name";
           update webhooks set stops="$stops" where webhookName="$name";
        EOF;

        return self::$database->exec($updateWebhookData);
    }
}