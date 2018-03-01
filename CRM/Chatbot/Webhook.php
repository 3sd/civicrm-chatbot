<?php
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;

class CRM_Chatbot_Webhook {

  public static function facebook()
  {

    $config = [
      'facebook' => [
        'token' => civicrm_api3('setting', 'getvalue', ['name' => 'chatbot_facebook_page_access_token']),
        'app_secret' => civicrm_api3('setting', 'getvalue', ['name' => 'chatbot_facebook_app_secret']),
        'verification' => civicrm_api3('setting', 'getvalue', ['name' => 'chatbot_facebook_verify_token'])
      ]
    ];

    DriverManager::loadDriver(\BotMan\Drivers\Facebook\FacebookDriver::class);
    $botman = BotManFactory::create($config);
    $botman->listen();
  }

}
