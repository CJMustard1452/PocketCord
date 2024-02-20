<?php

namespace CJMustard1452\PocketCord\Listener;

use CJMustard1452\PocketCord\Webhook\WebhookAPI;
use pocketmine\player\Player;
use SQLite3;

class SetupListener {

    public const CREATE = 0;
    public const EDIT = 1;

    private static array $playerArray = [];

    public function __construct(Player $player, Int $type, array $data) {
        self::$playerArray[$player->getName()] = $data;
        self::$playerArray[$player->getName()]["type"] = $type;

        if(!$data['url']) {
            $player->sendMessage("§8(§3§cPocket§8Cord §7Webhook Management§8)§7 Please enter a Webhook-URL into the chat.");
            return; 
        }

        if(!$data['avatar_url']) {
            $player->sendMessage("§8(§3§cPocket§8Cord §7Webhook Management§8)§7 Please enter an Avatar-URL into the chat.");
            return;
        }
    }

    public static function isWaiting(Player $player, String $setting): bool {
        if(!isset(self::$playerArray[$player->getName()])) return false;
        self::updateSettings($player, $setting);
        return true;
    }

    public static function updateSettings(Player $player, String $setting) {
        $data = self::$playerArray[$player->getName()];

        if($data['type'] == self::CREATE) {
            if(!$data['url']) {
                $data['url'] = true;
                WebhookAPI::createWebhook($data['name'], $setting, '', $data['tasks']);
                if(!$data['avatar_url']) return $player->sendMessage("§8(§3§cPocket§8Cord §7Webhook Management§8)§7 Set Webhook URL, please enter an Avatar-URL into the chat.");
                unset(self::$playerArray[$player->getName()]);
                return $player->sendMessage("§8(§3§cPocket§8Cord §7Webhook Management§8)§7 Set Webhook-URL.");
            }

            if(!$data['avatar_url']) {
                WebhookAPI::updateWebhookImageURL($data['name'], $setting);
                unset(self::$playerArray[$player->getName()]);
                return $player->sendMessage("§8(§3§cPocket§8Cord §7Webhook Management§8)§7 Updated Avatar-URL.");
            }
        }

        if($data['type'] == self::EDIT) {
            if(!$data['url']) {
                $data['url'] = true;
                WebhookAPI::updateWebhookURL($data['name'], $setting);
                if(!$data['avatar_url']) return $player->sendMessage("§8(§3§cPocket§8Cord §7Webhook Management§8)§7 Set Webhook URL, please enter an Avatar-URL");
                unset(self::$playerArray[$player->getName()]);
                return $player->sendMessage("§8(§3§cPocket§8Cord §7Webhook Management§8)§7 Set Webhook-URL.");
            }
            
            if(!$data['avatar_url']) {
                $data['avatar_url'] = true;
                WebhookAPI::updateWebhookImageURL($data['name'], $setting);
                unset(self::$playerArray[$player->getName()]);
                return $player->sendMessage("§8(§3§cPocket§8Cord §7Webhook Management§8)§7 Updated Avatar-URL.");
            } 
        }
    }
}
