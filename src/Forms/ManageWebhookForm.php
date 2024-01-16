<?php

namespace CJMustard1452\PocketCord\Forms;

use CJMustard1452\PocketCord\Webhook\WebhookAPI;
use CJMustard1452\PocketCord\Library\FormAPI\BaseForm;
use CJMustard1452\PocketCord\Library\FormAPI\CustomForm;
use CJMustard1452\PocketCord\Listener\SetupListener;

class ManageWebhookForm extends BaseForm {

    public function sendForm(): void {
        $form = new CustomForm(function($player, $data) { 
            if(!isset($data)) return;

            if(isset($data['name']) && $data['name']) {
                if(WebhookAPI::getWebhook($data['name'])) return $player->sendMessage("§8(§3Pocket§tCord§8)§7 There is already a webhook with this name.");
                if(!ctype_space($data['name'])) WebhookAPI::updateWebhookName($this->data['name'], $data['name']);
                $this->data['name'] = $data['name'];
            }

            $tasks = [];
            foreach($data as $taskname => $task) {
                if($taskname == 'name' || $taskname == 'url' || $taskname == 'avatar_url' || $taskname == 'label') continue;
                if($task) $tasks[] = $taskname;
            }

            WebhookAPI::updateWebhookTasks($this->data['name'], $tasks);

            ($data['url']) ? $url = false : $url = true;
            ($data['avatar_url']) ? $aurl = false : $aurl = true;

            if(isset($data['avatar_url']) || isset($data['url'])) {
                $webhookData = [
                    "name" => $this->data['name'],
                    "tasks" => $tasks,
                    'url' => $url,
                    'avatar_url' => $aurl
                ];

                new SetupListener($player, SetupListener::EDIT, $webhookData);
            } else $player->sendMessage("§8(§3Pocket§tCord§8)§7 This webhook has been updated.");
        });

        if(!$webhookObject = WebhookAPI::getWebhook($this->data['name'])) return;

        $form->setTitle('§8(§3Pocket§tCord§8)');

        $form->addInput('Required', 'Webhook Name', null, 'name');
        $form->addToggle('Edit Webhook URL', false, "url");
        $form->addToggle('Edit Webhook Avatar', false, "avatar_url");

        $form->addLabel("§7(§cNOTICE§7) §8Toggle what you want the server to flag.", 'label');

        $form->addToggle('Stops', in_array(WebhookAPI::STOP, $webhookObject->tasks), WebhookAPI::STOP);
        $form->addToggle('Starts', in_array(WebhookAPI::START, $webhookObject->tasks), WebhookAPI::START);
        $form->addToggle('Joins', in_array(WebhookAPI::JOIN, $webhookObject->tasks), WebhookAPI::JOIN);
        $form->addToggle('Leaves', in_array(WebhookAPI::LEAVE, $webhookObject->tasks), WebhookAPI::LEAVE);
        $form->addToggle('Deaths', in_array(WebhookAPI::DEATH, $webhookObject->tasks), WebhookAPI::DEATH);
        $form->addToggle('Chats', in_array(WebhookAPI::CHAT, $webhookObject->tasks), WebhookAPI::CHAT);
        $form->addToggle('Commands', in_array(WebhookAPI::COMMAND, $webhookObject->tasks), WebhookAPI::COMMAND);
        $form->addToggle('Kills', in_array(WebhookAPI::KILL, $webhookObject->tasks), WebhookAPI::KILL);

        $this->player->sendForm($form);
    }
}