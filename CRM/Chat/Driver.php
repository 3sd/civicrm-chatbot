<?php
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Facebook\FacebookDriver;

class CRM_Chat_Driver {

  const SHORT_NAMES = [
    'Facebook' => 'Facebook',
    'CRM_Chat_Driver_CiviSMS' => 'CiviSMS',
    'CRM_Chat_Driver_DevChat' => 'DevChat'
  ];

  static function getServiceName($driver) {

    if(!isset(self::SHORT_NAMES[$driver::DRIVER_NAME])) {
      throw new \Exception('Could not find short name for CiviCRM chatbot driver: ' . $driver);
    }

    return self::SHORT_NAMES[$driver::DRIVER_NAME];

  }

}
