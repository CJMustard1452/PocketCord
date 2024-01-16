<?php

namespace CJMustard1452\PocketCord\Forms;

use CJMustard1452\PocketCord\Library\FormAPI\BaseForm;
use CJMustard1452\PocketCord\Library\FormAPI\SimpleForm;
use CJMustard1452\PocketCord\Webhook\WebhookAPI;

class RemoveWebhookForm extends BaseForm {

    public function sendForm(): void {
        $form = new SimpleForm(function($player, $data) { 
            if(!isset($data)) return;
            if($data == 1) return $player->sendMessage("§8(§3§cPocket§8Cord §7Webhook Management§8)§7 Canceled action.");

            WebhookAPI::deleteWebhook($this->data["name"]);
            $player->sendMessage("§8(§3§cPocket§8Cord §7Webhook Management§8)§7 Successfully deleted webhook.");
        });

        $form->setTitle('§cPocket§8Cord §rWebhook Management');
        $form->setContent("Are you sure you want to do this?");
        $form->addButton('§aYes', 0, 'textures/ui/realms_green_check.png', 0);
        $form->addButton('§cNo', 0, 'textures/ui/redX1.png', 1);

        $this->player->sendForm($form);
    }
}