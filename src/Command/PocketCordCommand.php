<?php

namespace CJMustard1452\PocketCord\Command;

use CJMustard1452\PocketCord\Forms\PocketCordForm;
use pocketmine\command\Command;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;

class PocketCordCommand extends Command {

    public function __construct() {
        parent::__construct("pocketcord", "PocketCord panel access");
        $this->setPermission('pocketcord.cmd');
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if(!$sender instanceof Player) {
            $sender->sendMessage('§8(§3PocketCord§8) You can only use this command in game');
            return;
        }

        new PocketCordForm($sender);
    }
}