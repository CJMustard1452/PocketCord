<?php

namespace CJMustard1452\PocketCord\Discord;

class CurlRequest {

    public function __construct(
        String $url,
        String $content,
        ?String $name = "PocketCord Webhook",
        ?String $imageURL = null
    ) {

        if($imageURL == null) $imageURL = "https://cdn.discordapp.com/attachments/1141574935836364920/1177776714106015754/download.jpg?ex=6573bd0a&is=6561480a&hm=6ce0ebff1eed685e73f465f8d3e8e5db877adca8efe12f239d806e0a3ab9ce0c&";
        if(!str_starts_with($imageURL, 'https://')) $imageURL = "https://cdn.discordapp.com/attachments/1141574935836364920/1177776714106015754/download.jpg?ex=6573bd0a&is=6561480a&hm=6ce0ebff1eed685e73f465f8d3e8e5db877adca8efe12f239d806e0a3ab9ce0c&";

        $data = [
            "username" => $name,
            "avatar_url" => $imageURL,
            "content" => $content
        ];

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        curl_exec($ch);
    }
}