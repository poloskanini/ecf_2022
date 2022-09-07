<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
  private $api_key = '9643dbf3120f04b54c7c81bb32565df8';
  private $api_key_secret = 'c38f88aa336083980bfcfd690372ba28';

  public function send($to_email, $to_name, $subject, $content)
  {
    $mj = new Client($this->api_key, $this->api_key_secret, true,['version' => 'v3.1']);
    // $mj = new \Mailjet\Client(getenv('MJ_APIKEY_PUBLIC'), getenv('MJ_APIKEY_PRIVATE'),true,['version' => 'v3.1']);
    $body = [
        'Messages' => [
            [
                'From' => [
                    'Email' => "nicolasbarthes.lana@gmail.com",
                    'Name' => "STUDI FITNESS"
                ],
                'To' => [
                    [
                        'Email' => $to_email,
                        'Name' => $to_name
                    ]
                ],
                'TemplateID' => 4176231,
                'TemplateLanguage' => true,
                'Subject' => $subject,
                'Variables' => [
                  'content' => $content
                ]
            ]
        ]
    ];
    $response = $mj->post(Resources::$Email, ['body' => $body]);
    $response->success();
  }
}