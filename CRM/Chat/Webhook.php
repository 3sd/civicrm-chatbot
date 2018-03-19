<?php

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Facebook\FacebookDriver;

class CRM_Chat_Webhook {

  public static function facebook() {
    CRM_Chat_Botman::createListener('Facebook');
  }

  public static function devchat() {
    CRM_Chat_Botman::createListener('DevChat');
  }
}
