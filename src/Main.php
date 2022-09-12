<?php

declare(strict_types=1);

namespace CJMustard1452\PocketCord;

use CJMustard1452\PocketCord\Tasks\WebhookTask;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\event\Listener;

class Main extends PluginBase implements Listener{

	public $config;

	public function onEnable() :void{
		if(!is_dir($this->getDataFolder() . 'TemporaryProcessing')) mkdir($this->getDataFolder() . 'TemporaryProcessing');
		if(!file_exists($this->getDataFolder() . 'TemporaryProcessing/CURLOPTSEND.json')) fopen($this->getDataFolder() . 'TemporaryProcessing/CURLOPTSEND.json', 'w+');
		if(!file_exists($this->getDataFolder() . 'TemporaryProcessing/config.json')) fopen($this->getDataFolder() . 'TemporaryProcessing/config.json', 'w+');
		$this->config = json_decode(file_get_contents($this->getDataFolder() . 'TemporaryProcessing/config.json'), true);
		if(!isset($this->config['WebhookURL']))  $this->getLogger()->info('§bPocketCord is not yet logging chats; run /pocketcord to enable chat logging.');
		file_put_contents($this->getDataFolder() . 'TemporaryProcessing/CURLOPTSEND.json', '');
		$this->getScheduler()->scheduleRepeatingTask(new WebhookTask($this), 60);
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		if(isset($args[0]) && isset($args[1])){
			$this->config['WebhookName'] = $args[0];
			$this->config['WebhookURL'] = $args[1];
			file_put_contents($this->getDataFolder() . 'TemporaryProcessing/config.json', json_encode($this->config));
			$sender->sendMessage('§7[§bPocketCord§7]§a PocketCord is now setup!');
		}else $sender->sendMessage('§7[§bPocketCord§7]§c /pocketcord (Webhook Name) (WebhookURL)');
		return true;
	}

	public function onChat(PlayerChatEvent $event){
		$msg = str_replace('@everyone', '@ everyone', $event->getMessage());
		$msg = str_replace('@here', '@ here', $msg);
		$msg = str_replace('<@', '<@ ', $msg);
		file_put_contents($this->getDataFolder() . 'TemporaryProcessing/CURLOPTSEND.json', file_get_contents($this->getDataFolder() . 'TemporaryProcessing/CURLOPTSEND.json') . "\n->**" . $event->getPlayer()->getName() . '**> ' . $msg);
	}
}