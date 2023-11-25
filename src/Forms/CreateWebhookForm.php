<?php

namespace CJMustard1452\PocketCord\Forms;

use CJMustard1452\PocketCord\Webhook\WebhookAPI;
use CJMustard1452\PocketCord\Library\FormAPI\BaseForm;
use CJMustard1452\PocketCord\Library\FormAPI\CustomForm;
use CJMustard1452\PocketCord\Listener\SetupListener;

class CreateWebhookForm extends BaseForm {

    public function sendForm(): void {
        $form = new CustomForm(function($player, $data) { 
            if(!isset($data)) return;
            if(!isset($data['name']) || $data['name'] == "" || ctype_space($data['name'])) return $player->sendMessage("§8(§3Pocket§tCord§8)§7 You need to enter a name for your webhook.");
            if(WebhookAPI::getWebhook($data['name'])) return $player->sendMessage("§8(§3Pocket§tCord§8)§7 There is already a webhook with this name.");
            
            $tasks = [];
            foreach($data as $taskname => $task) {
                if($taskname == 'name' || $taskname == 'label') continue;
                if($task) $tasks[] = $taskname;
            }

            ($data['avatar_url']) ? $aurl = false : $aurl = true;

            $webhookData = [
                "name" => $data['name'],
                "tasks" => $tasks,
                'url' => false,
                'avatar_url' => $aurl
            ];

            new SetupListener($player, SetupListener::CREATE, $webhookData);
        });

        $form->setTitle('§8(§3Pocket§tCord§8)');

        $form->addInput('Required', 'Webhook Name', null, 'name');
        $form->addToggle('Avatar URL', false, 'avatar_url');

        $form->addLabel("§7(§cNOTICE§7) §8Toggle what you want the server to flag.", 'label');

        $form->addToggle('Stops', false, WebhookAPI::STOP);
        $form->addToggle('Starts', false, WebhookAPI::START);
        $form->addToggle('Joins', false, WebhookAPI::JOIN);
        $form->addToggle('Leaves', false, WebhookAPI::LEAVE);
        $form->addToggle('Deaths', false, WebhookAPI::DEATH);
        $form->addToggle('Chats', false, WebhookAPI::CHAT);
        $form->addToggle('Commands', false, WebhookAPI::COMMAND);
        $form->addToggle('Kills', false, WebhookAPI::KILL);

        $this->player->sendForm($form);
    }
}