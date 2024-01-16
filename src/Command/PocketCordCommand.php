<?php

namespace CJMustard1452\PocketCord\Command;

use CJMustard1452\PocketCord\Forms\PocketCordForm;
use CJMustard1452\PocketCord\Loader;
use pocketmine\command\Command;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginOwned;

class PocketCordCommand extends Command implements PluginOwned {

    private Loader $plugin;

    public function __construct(Loader $loader) {
        $this->plugin = $loader;

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

    public function getOwningPlugin(): Loader {
		return $this->plugin;
	}
}