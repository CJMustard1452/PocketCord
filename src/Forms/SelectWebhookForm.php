<?php

namespace CJMustard1452\PocketCord\Forms;

use CJMustard1452\PocketCord\Library\FormAPI\BaseForm;
use CJMustard1452\PocketCord\Library\FormAPI\SimpleForm;
use CJMustard1452\PocketCord\Webhook\WebhookAPI;

class SelectWebhookForm extends BaseForm {

    public function sendForm(): void {
        $form = new SimpleForm(function($player, $data) { 
            if(!isset($data)) return;

            new ManageWebhookForm($player, ['name' => $data]);
        });

        $form->setTitle('§8(§3Pocket§tCord§8)');
        
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