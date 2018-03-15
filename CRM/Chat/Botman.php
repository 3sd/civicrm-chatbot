<?php
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
/**
 * "Wraps" botman
 */

class CRM_Chat_Botman {

  static function createListener($config, $driver) {

    DriverManager::loadDriver(\BotMan\Drivers\Facebook\FacebookDriver::class);
    $botman = BotManFactory::create($config, new CRM_Chat_Cache);

    // Received
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
  }

}
