<?php
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Facebook\FacebookDriver;

class CRM_Chat_Botman {

  // Might want to turn into option groups at some point
  static function getAllServices(){
    $services = [
      'Facebook' => 'Facebook',
      'CiviSMS' => 'CiviSMS',
    ];
    if(Civi::settings()->get('debug_enabled')){
      $services['DevChat'] = 'DevChat';
    }
    return $services;
  }

  static function get($service) {

    $driver = self::getDriver($service);
    $config = self::getConfig($service);


    DriverManager::loadDriver($driver);
    $botman = BotManFactory::create($config, new CRM_Chat_Cache);

    return $botman;

  }

  static function getDriver($service) {

    switch ($service) {

      case 'Facebook':

        return FacebookDriver::class;

      case 'CiviSMS':

        return CRM_Chat_Driver_CiviSMSDriver::class;

      case 'DevChat':

        return CRM_Chat_Driver_DevChatDriver::class;

    }

  }

  static function getConfig($service) {

    switch ($service) {

      case 'Facebook':
        return [
          'facebook' => [
            'token' => civicrm_api3('setting', 'getvalue', ['name' => 'chatbot_facebook_page_access_token']),
            'app_secret' => civicrm_api3('setting', 'getvalue', ['name' => 'chatbot_facebook_app_secret']),
            'verification' => civicrm_api3('setting', 'getvalue', ['name' => 'chatbot_facebook_verify_token'])
          ]
        ];

      case 'DevChat':
        return [
          'endpoint' => civicrm_api3('setting', 'getvalue', ['name' => 'chatbot_devchat_endpoint'])
        ];

      case 'CiviSMS':

        return [
          'authentication_token' => civicrm_api3('setting', 'getvalue', ['name' => 'chatbot_civisms_authentication_token'])
        ];

    }

  }

}
