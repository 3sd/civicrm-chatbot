<?php
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
/**
 * "Wraps" botman
 */

class CRM_Chat_Botman {

  const SERVICE_NAMES = [
    'Facebook' => 'Facebook',
    'CRM_Chat_Driver_CiviSMS' => 'CiviSMS',
    'CRM_Chat_Driver_DevChat' => 'DevChat'
  ];


  static function createListener($driver) {

    $botman = self::getBot($driver);

    $botman->middleware->received(new CRM_Chat_Middleware_Identify());
    $botman->middleware->received(new CRM_Chat_Middleware_IncomingLog());

    // Sending
    $botman->middleware->sending(new CRM_Chat_Middleware_OutgoingLog());

    foreach(CRM_Chat_BAO_Triggers::getAll() as $trigger){
      $botman->hears($trigger['text'], function ($bot, $message) {
        $bot->startConversation(new CRM_Chat_Conversation($trigger['conversation_id']));
      });
    }

    $botman->listen();
  static function getBot($service) {

    $driver = self::getDriver($service);
    $config = self::getConfig($service);

    DriverManager::loadDriver($driver);
    $botman = BotManFactory::create($config);

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

      default:

        return [];

    }

  }

  static function shortName($driver) {

    if(!isset(self::SHORT_NAMES[$driver::DRIVER_NAME])) {
      throw new \Exception('Could not find short name for CiviCRM chatbot driver: ' . $driver);
    }

    return self::SHORT_NAMES[$driver::DRIVER_NAME];

  }

}
