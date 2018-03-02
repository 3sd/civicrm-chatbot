<?php
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
/**
 * "Wraps" botman
 */

class CRM_Chatbot_Botman {

  static function createListener($config, $driver) {

    DriverManager::loadDriver(\BotMan\Drivers\Facebook\FacebookDriver::class);
    $botman = BotManFactory::create($config);

    $botman->middleware->received(new CRM_Chatbot_Middleware_Identify());
    $botman->listen();
  }

}
