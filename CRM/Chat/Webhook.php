<?php

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Facebook\FacebookDriver;

class CRM_Chat_Webhook {

  public static function facebook() {
    CRM_Chat_Listen::create('Facebook', $_SERVER['REQUEST_METHOD'] == 'GET');
  }

  public static function devchat() {
    CRM_Chat_Listen::create('DevChat');
  }

  public static function civisms() {
    CRM_Chat_Listen::create('CiviSMS');
  }
}
