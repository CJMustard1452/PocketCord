<?php

namespace CJMustard1452\PocketCord\Listener;

use CJMustard1452\PocketCord\Webhook\WebhookAPI;
use CJMustard1452\PocketCord\Webhook\WebhookMessage;
use pocketmine\event\Listener;
use CJMustard1452\PocketCord\Listener\SetupListener;
use CJMustard1452\PocketCord\Loader;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\CommandEvent;
use pocketmine\player\Player;

class EventListener implements Listener {

    public function onChat(PlayerChatEvent $playerChatEvent) {
        if(SetupListener::isWaiting($playerChatEvent->getPlayer(), $playerChatEvent->getMessage())) return $playerChatEvent->cancel();

        $format = Loader::$config->get(WebhookMessage::FORMATS[WebhookAPI::CHAT]);
        $message = str_replace('{player_name}', $playerChatEvent->getPlayer()->getName(), $format);
        $message = str_replace('{message}', $playerChatEvent->getMessage(), $message);

        WebhookMessage::applyMessage(WebhookAPI::CHAT, $message);
    }

    public function onCommand(CommandEvent $commandEvent): void {
        $format = Loader::$config->get(WebhookMessage::FORMATS[WebhookAPI::COMMAND]);
        $message = str_replace('{player_name}', $commandEvent->getSender()->getName(), $format);
        $message = str_replace('{message}', $commandEvent->getCommand(), $message);

        WebhookMessage::applyMessage(WebhookAPI::COMMAND, $message);
    }

    public function onJoin(PlayerJoinEvent $playerJoinEvent): void {
        $format = Loader::$config->get(WebhookMessage::FORMATS[WebhookAPI::JOIN]);
        $message = str_replace('{player_name}', $playerJoinEvent->getPlayer()->getName(), $format);

        WebhookMessage::applyMessage(WebhookAPI::JOIN, $message);
    }

    public function onQuit(PlayerQuitEvent $playerQuitEvent): void {
        $format = Loader::$config->get(WebhookMessage::FORMATS[WebhookAPI::LEAVE]);
        $message = str_replace('{player_name}', $playerQuitEvent->getPlayer()->getName(), $format);

        WebhookMessage::applyMessage(WebhookAPI::LEAVE, $message);
    }

    public function onDeath(PlayerDeathEvent $playerDeathEvent): void {
        if($playerDeathEvent->getPlayer()->getLastDamageCause() instanceof EntityDamageByEntityEvent){
            if(!$playerDeathEvent->getPlayer()->getLastDamageCause()->getEntity() instanceof Player) return;

            $format = Loader::$config->get(WebhookMessage::FORMATS[WebhookAPI::KILL]);
            $message = str_replace('{dead_name}', $playerDeathEvent->getPlayer()->getName(), $format);
            $message = str_replace('{killer_name}', $playerDeathEvent->getPlayer()->getLastDamageCause()->getEntity()->getName(), $message);

            WebhookMessage::applyMessage(WebhookAPI::KILL, $message);
        } else {

            $format = Loader::$config->get(WebhookMessage::FORMATS[WebhookAPI::DEATH]);
            $message = str_replace('{player_name}', $playerDeathEvent->getPlayer()->getName(), $format);

            WebhookMessage::applyMessage(WebhookAPI::DEATH, $message);
        }
    }
}