<?php

namespace CJMustard1452\PocketCord\Forms;

use CJMustard1452\PocketCord\Library\FormAPI\BaseForm;
use CJMustard1452\PocketCord\Library\FormAPI\SimpleForm;
use CJMustard1452\PocketCord\Webhook\WebhookAPI;

class SelectWebhookForm extends BaseForm {

    public const MANAGE = 0;
    public const REMOVE = 1;

    public function sendForm(): void {
        $form = new SimpleForm(function($player, $data) { 
            if(!isset($data)) return;

            if($this->data["Type"] == self::MANAGE) new ManageWebhookForm($player, ['name' => $data]);
            if($this->data["Type"] == self::REMOVE) new RemoveWebhookForm($player, ['name' => $data]);
        });

        $form->setTitle('§cPocket§8Cord §rWebhook Management');
        
        if(empty(WebhookAPI::$webhooks)) {
            $form->setContent('§7(§cNOTICE§7) §8There are no active Web-Hooks...');
        } else {
            foreach(WebhookAPI::$webhooks as $webhookObject) {
                $form->addButton("§t" . $webhookObject->name, -1, "", $webhookObject->name);
            }
        }

        $this->player->sendForm($form);
    }
}