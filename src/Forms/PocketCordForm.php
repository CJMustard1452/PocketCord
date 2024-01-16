<?php

namespace CJMustard1452\PocketCord\Forms;

use CJMustard1452\PocketCord\Library\FormAPI\BaseForm;
use CJMustard1452\PocketCord\Library\FormAPI\SimpleForm;

class PocketCordForm extends BaseForm {

    public function sendForm(): void {
        $form = new SimpleForm(function($player, $data) { 
            if(!isset($data)) return;
            if($data == 0) return new CreateWebhookForm($player);
            if($data == 1) return new SelectWebhookForm($player, ['Type' => SelectWebhookForm::MANAGE]);
            if($data == 2) return new SelectWebhookForm($player, ['Type' => SelectWebhookForm::REMOVE]);
        });

        $form->setTitle('§cPocket§8Cord §rWebhook Management');
        $form->addButton('§bCreate Webhook', 0, 'textures/ui/plus.png', 0);
        $form->addButton('§cManage Webhooks', 0, 'textures/gui/newgui/Wrenches2.png', 1);
        $form->addButton('§4Remove Webhooks', 0, 'textures/ui/trash.png', 2);

        $this->player->sendForm($form);
    }
}