<?php
use BotMan\BotMan\Interfaces\Middleware\Received;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\BotMan;

class CRM_Chatbot_Middleware_IncomingLog implements Received {

  public function received(IncomingMessage $message, $next, BotMan $bot) {
    CRM_Chatbot_Logger::log("Message <-- contact_id ".$message->getExtras('contact_id') . ':' . $message->getText());
    return $next($message);
  }


}
