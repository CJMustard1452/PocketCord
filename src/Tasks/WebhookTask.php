<?php

namespace CJMustard1452\PocketCord\Tasks;

use pocketmine\scheduler\Task;
use pocketmine\plugin\Plugin;

class WebhookTask extends Task{

	public $plugin;
	public $config;
	public $ticks;

	public function __construct(Plugin $plugin){
		$this->plugin = $plugin;
	}

	public function onRun() : void{
		$this->config = json_decode(file_get_contents($this->plugin->getDataFolder() . 'TemporaryProcessing/config.json'), true);
		if(isset($this->config['WebhookURL']) && file_get_contents($this->plugin->getDataFolder() . "TemporaryProcessing/CURLOPTSEND.json") == true){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->config['WebhookURL']);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json; charset=utf-8']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['username' => $this->config['WebhookName'], 'content' => file_get_contents($this->plugin->getDataFolder() . "TemporaryProcessing/CURLOPTSEND.json")]));
			$response = curl_exec($ch);
			file_put_contents($this->plugin->getDataFolder() . 'TemporaryProcessing/CURLOPTSEND.json', '');
		}
	}
}